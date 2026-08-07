<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $revenue = [
            'today' => Invoice::whereDate('created_at', $today)->sum('total'),
            'week' => Invoice::whereBetween('created_at', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()])->sum('total'),
            'month' => Invoice::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->sum('total'),
            'year' => Invoice::whereYear('created_at', $today->year)->sum('total'),
        ];

        // Last 6 months revenue series
        $months = collect(range(5, 0))->map(function ($i) {
            $m = Carbon::today()->subMonths($i);
            return [
                'label' => $m->format('M'),
                'total' => (float) Invoice::whereMonth('created_at', $m->month)->whereYear('created_at', $m->year)->sum('total'),
            ];
        });
        $maxMonth = max($months->max('total'), 1);

        $topServices = InvoiceItem::where('type', 'service')
            ->select('name', DB::raw('SUM(qty) as qty'), DB::raw('SUM(line_total) as revenue'))
            ->groupBy('name')->orderByDesc('revenue')->limit(8)->get();

        $topProducts = InvoiceItem::where('type', 'product')
            ->select('name', DB::raw('SUM(qty) as qty'), DB::raw('SUM(line_total) as revenue'))
            ->groupBy('name')->orderByDesc('revenue')->limit(8)->get();

        $staffRevenue = InvoiceItem::whereNotNull('staff_id')
            ->select('staff_id', DB::raw('SUM(line_total) as revenue'))
            ->with('staff')->groupBy('staff_id')->orderByDesc('revenue')->limit(8)->get();

        $topCustomers = Invoice::whereNotNull('customer_id')
            ->select('customer_id', DB::raw('SUM(total) as spent'), DB::raw('COUNT(*) as visits'))
            ->with('customer')->groupBy('customer_id')->orderByDesc('spent')->limit(8)->get();

        $paymentMix = DB::table('payments')->select('method', DB::raw('SUM(amount) as total'))->groupBy('method')->get();

        return view('admin.reports.index', compact('revenue', 'months', 'maxMonth', 'topServices', 'topProducts', 'staffRevenue', 'topCustomers', 'paymentMix'));
    }
}
