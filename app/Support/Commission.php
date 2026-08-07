<?php

namespace App\Support;

use App\Models\InvoiceItem;
use App\Models\Staff;

class Commission
{
    /**
     * Compute commission for a staff member over a date range,
     * based on the invoice lines attributed to them at the POS.
     */
    public static function forStaff(Staff $staff, $start, $end): array
    {
        $rows = InvoiceItem::where('invoice_items.staff_id', $staff->id)
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->selectRaw('invoice_items.type, SUM(invoice_items.line_total) as rev')
            ->groupBy('invoice_items.type')
            ->pluck('rev', 'type');

        $serviceRev = (float) ($rows['service'] ?? 0);
        $productRev = (float) ($rows['product'] ?? 0);
        $total = $serviceRev + $productRev;

        if ($staff->commission_type === 'split') {
            $commission = $serviceRev * ($staff->commission_percent / 100)
                        + $productRev * ($staff->product_commission_percent / 100);
        } else {
            $commission = $total * ($staff->commission_percent / 100);
        }

        $targetMet = $staff->target_amount > 0 && $total >= $staff->target_amount;
        $bonus = $targetMet ? (float) $staff->target_bonus : 0.0;

        return [
            'service_revenue' => round($serviceRev, 2),
            'product_revenue' => round($productRev, 2),
            'total_revenue' => round($total, 2),
            'commission' => round($commission, 2),
            'target_bonus' => round($bonus, 2),
            'target_met' => $targetMet,
            'total_earning' => round($commission + $bonus, 2),
        ];
    }
}
