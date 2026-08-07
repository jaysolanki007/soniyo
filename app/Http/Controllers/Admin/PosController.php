<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function create()
    {
        return view('admin.pos.create', [
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
            'staff' => Staff::where('is_active', true)->orderBy('name')->get(),
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string'],
            'staff_id' => ['nullable', 'exists:staff,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.type' => ['required', 'in:service,product'],
            'items.*.item_id' => ['nullable', 'integer'],
            'items.*.name' => ['required', 'string'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'offer_code' => ['nullable', 'string'],
            'tax_percent' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,card,upi,wallet,bank'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoice = DB::transaction(function () use ($data, $request) {
            $subtotal = collect($data['items'])->sum(fn ($i) => $i['qty'] * $i['unit_price']);
            $discount = (float) ($data['discount'] ?? 0);
            $taxable = max(0, $subtotal - $discount);
            $tax = round($taxable * ((float) ($data['tax_percent'] ?? 0)) / 100, 2);
            $total = $taxable + $tax;

            $invoice = Invoice::create([
                'number' => 'INV-'.date('Ymd').'-'.str_pad((string) (Invoice::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $data['customer_id'] ?? null,
                'customer_name' => $data['customer_name'] ?? optional(Customer::find($data['customer_id'] ?? null))->name ?? 'Walk-in',
                'staff_id' => $data['staff_id'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'offer_code' => $data['offer_code'] ?? null,
                'tax' => $tax,
                'total' => $total,
                'paid' => $total,
                'status' => 'paid',
                'notes' => $data['notes'] ?? null,
                'created_by' => $request->user()->id,
            ]);

            foreach ($data['items'] as $i) {
                $invoice->items()->create([
                    'type' => $i['type'],
                    'item_id' => $i['item_id'] ?? null,
                    'name' => $i['name'],
                    'qty' => $i['qty'],
                    'unit_price' => $i['unit_price'],
                    'line_total' => $i['qty'] * $i['unit_price'],
                    'staff_id' => $data['staff_id'] ?? null,
                ]);

                // deduct product stock
                if ($i['type'] === 'product' && ! empty($i['item_id'])) {
                    $product = Product::find($i['item_id']);
                    if ($product) {
                        $product->decrement('stock_qty', $i['qty']);
                        $product->movements()->create(['type' => 'out', 'qty' => $i['qty'], 'reason' => 'POS sale', 'reference' => $invoice->number]);
                    }
                }
            }

            $invoice->payments()->create([
                'amount' => $total,
                'method' => $data['payment_method'],
                'paid_at' => now(),
            ]);

            // update offer usage
            if (! empty($data['offer_code'])) {
                Offer::where('code', $data['offer_code'])->increment('used_count');
            }

            // update customer CRM totals
            if ($invoice->customer_id) {
                $c = Customer::find($invoice->customer_id);
                $c->increment('total_spent', $total);
                $c->increment('visit_count');
                $c->increment('loyalty_points', (int) floor($total / 10)); // 1 pt per ₹10
                $c->update(['last_visit_at' => now()]);
            }

            return $invoice;
        });

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'Sale completed — invoice '.$invoice->number.' created.');
    }
}
