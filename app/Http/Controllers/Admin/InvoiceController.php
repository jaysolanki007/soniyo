<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('customer')
            ->when($request->get('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15)->withQueryString();

        $totals = [
            'count' => Invoice::count(),
            'revenue' => Invoice::sum('total'),
            'today' => Invoice::whereDate('created_at', today())->sum('total'),
        ];

        return view('admin.invoices.index', compact('invoices', 'totals'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'payments', 'customer', 'staff']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted.');
    }
}
