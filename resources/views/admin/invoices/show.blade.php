@extends('admin.layout')
@section('title', 'Invoice '.$invoice->number)

@section('actions')
<button onclick="window.print()" class="px-5 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">🖨 Print</button>
<a href="{{ route('admin.pos.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Sale</a>
@endsection

@section('content')
<div id="invoice" class="max-w-2xl mx-auto rounded-xl border border-white/10 bg-ink-800 p-8 print:bg-white print:text-black">
  <div class="flex items-start justify-between mb-8">
    <div class="flex items-center gap-3">
      <img src="{{ asset('assets/soniyo-logo.png') }}" onerror="this.onerror=null;this.src='{{ asset('assets/soniyo-emblem.svg') }}'" class="h-14 w-auto">
      <div>
        <div class="serif text-2xl text-gold-soft">SoNiYo Beauty Salon</div>
        <div class="text-xs text-stone-500">{{ \App\Models\SiteSetting::get('contact_address','148 Madison Avenue, New York') }}</div>
      </div>
    </div>
    <div class="text-right">
      <div class="serif text-xl text-stone-100">INVOICE</div>
      <div class="text-xs text-stone-400 font-mono">{{ $invoice->number }}</div>
      <div class="text-xs text-stone-500">{{ $invoice->created_at->format('M d, Y g:i A') }}</div>
    </div>
  </div>

  <div class="flex justify-between text-sm mb-6">
    <div>
      <div class="text-[10px] uppercase tracking-wider text-stone-500 mb-1">Billed To</div>
      <div class="text-stone-100">{{ $invoice->customer_name ?: ($invoice->customer->name ?? 'Walk-in') }}</div>
      @if($invoice->customer)<div class="text-stone-500 text-xs">{{ $invoice->customer->phone }}</div>@endif
    </div>
    <div class="text-right">
      <div class="text-[10px] uppercase tracking-wider text-stone-500 mb-1">Served By</div>
      <div class="text-stone-200">{{ $invoice->staff->name ?? '—' }}</div>
    </div>
  </div>

  <table class="w-full text-sm mb-6">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/10">
      <tr><th class="py-2">Item</th><th class="py-2 text-center">Qty</th><th class="py-2 text-right">Price</th><th class="py-2 text-right">Total</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @foreach ($invoice->items as $it)
        <tr><td class="py-2 text-stone-200">{{ $it->name }} <span class="text-[10px] text-stone-500 uppercase">{{ $it->type }}</span></td>
          <td class="py-2 text-center text-stone-400">{{ $it->qty }}</td>
          <td class="py-2 text-right text-stone-400">₹{{ number_format($it->unit_price,2) }}</td>
          <td class="py-2 text-right text-stone-200">₹{{ number_format($it->line_total,2) }}</td></tr>
      @endforeach
    </tbody>
  </table>

  <div class="ml-auto w-64 text-sm space-y-1">
    <div class="flex justify-between text-stone-400"><span>Subtotal</span><span>₹{{ number_format($invoice->subtotal,2) }}</span></div>
    @if($invoice->discount>0)<div class="flex justify-between text-stone-400"><span>Discount {{ $invoice->offer_code ? '('.$invoice->offer_code.')' : '' }}</span><span>-₹{{ number_format($invoice->discount,2) }}</span></div>@endif
    @if($invoice->tax>0)<div class="flex justify-between text-stone-400"><span>Tax</span><span>₹{{ number_format($invoice->tax,2) }}</span></div>@endif
    <div class="flex justify-between text-lg text-gold-soft serif border-t border-white/10 pt-2"><span>Total</span><span>₹{{ number_format($invoice->total,2) }}</span></div>
    <div class="flex justify-between text-emerald-300"><span>Paid ({{ $invoice->payments->first()->method ?? '—' }})</span><span>₹{{ number_format($invoice->paid,2) }}</span></div>
  </div>

  <p class="text-center text-xs text-stone-500 mt-8">Thank you for visiting SoNiYo Beauty Salon ✦</p>
</div>

<style>@media print{aside,header,.totop{display:none!important}#invoice{border:none;color:#000}body{background:#fff}}</style>
@endsection
