@extends('admin.layout')
@section('title', 'Invoices')
@section('subtitle', 'Billing history')

@section('actions')
<a href="{{ route('admin.pos.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Sale (POS)</a>
@endsection

@section('content')
<div class="grid grid-cols-3 gap-4 mb-6">
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-stone-100">{{ $totals['count'] }}</div><div class="text-xs text-stone-500">Total Invoices</div></div>
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-gold-soft">₹{{ number_format($totals['revenue'],2) }}</div><div class="text-xs text-stone-500">Total Revenue</div></div>
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-emerald-300">₹{{ number_format($totals['today'],2) }}</div><div class="text-xs text-stone-500">Today</div></div>
</div>

<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr><th class="px-5 py-3">Invoice</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Date</th><th class="px-5 py-3">Total</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($invoices as $inv)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3 font-mono text-gold-soft text-xs">{{ $inv->number }}</td>
          <td class="px-5 py-3 text-stone-200">{{ $inv->customer_name ?: ($inv->customer->name ?? 'Walk-in') }}</td>
          <td class="px-5 py-3 text-stone-400">{{ $inv->created_at->format('M d, Y g:i A') }}</td>
          <td class="px-5 py-3 text-stone-100">₹{{ number_format($inv->total,2) }}</td>
          <td class="px-5 py-3"><span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 {{ $inv->status==='paid'?'text-emerald-300':'text-gold-soft' }}">{{ ucfirst($inv->status) }}</span></td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.invoices.show', $inv) }}" class="text-stone-400 hover:text-gold">View</a>
            <form action="{{ route('admin.invoices.destroy', $inv) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete invoice?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-5 py-10 text-center text-stone-500">No invoices yet. <a href="{{ route('admin.pos.create') }}" class="text-gold">Make a sale →</a></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $invoices->links() }}</div>
@endsection
