@extends('admin.layout')
@section('title', 'Reports & Analytics')
@section('subtitle', 'Business performance at a glance')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  @foreach (['today'=>'Today','week'=>'This Week','month'=>'This Month','year'=>'This Year'] as $k=>$label)
    <div class="rounded-xl border border-white/5 bg-gradient-to-br from-gold/10 to-ink-800 p-5">
      <div class="serif text-2xl text-gold-soft">₹{{ number_format($revenue[$k],2) }}</div>
      <div class="text-xs text-stone-500">{{ $label }}</div>
    </div>
  @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <!-- Monthly revenue bars -->
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-5">Revenue — Last 6 Months</h2>
    <div class="flex items-end gap-3 h-48">
      @foreach ($months as $m)
        <div class="flex-1 flex flex-col items-center justify-end gap-2">
          <div class="text-[10px] text-stone-400">₹{{ number_format($m['total'],0) }}</div>
          <div class="w-full rounded-t bg-gradient-to-t from-gold-deep to-gold-soft" style="height: {{ max(4, $m['total']/$maxMonth*160) }}px"></div>
          <div class="text-[10px] uppercase tracking-wider text-stone-500">{{ $m['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Payment mix -->
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-5">Payment Methods</h2>
    @php $payTotal = $paymentMix->sum('total') ?: 1; @endphp
    <div class="space-y-3">
      @forelse ($paymentMix as $p)
        <div>
          <div class="flex justify-between text-sm mb-1"><span class="text-stone-300 uppercase text-xs tracking-wider">{{ $p->method }}</span><span class="text-stone-400">₹{{ number_format($p->total,2) }}</span></div>
          <div class="h-2 rounded bg-white/5 overflow-hidden"><div class="h-full bg-gold" style="width: {{ $p->total/$payTotal*100 }}%"></div></div>
        </div>
      @empty
        <p class="text-stone-500 text-sm">No payments recorded yet.</p>
      @endforelse
    </div>
  </div>

  <!-- Top services -->
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-4">Top Services</h2>
    @forelse ($topServices as $s)
      <div class="flex justify-between py-2 border-b border-white/5 text-sm"><span class="text-stone-300">{{ $s->name }} <span class="text-stone-600">×{{ $s->qty }}</span></span><span class="text-gold-soft">₹{{ number_format($s->revenue,2) }}</span></div>
    @empty<p class="text-stone-500 text-sm">No sales yet.</p>@endforelse
  </div>

  <!-- Staff revenue -->
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-4">Revenue by Staff</h2>
    @forelse ($staffRevenue as $s)
      <div class="flex justify-between py-2 border-b border-white/5 text-sm"><span class="text-stone-300">{{ $s->staff->name ?? '—' }}</span><span class="text-gold-soft">₹{{ number_format($s->revenue,2) }}</span></div>
    @empty<p class="text-stone-500 text-sm">No data yet.</p>@endforelse
  </div>

  <!-- Top products -->
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-4">Top Products</h2>
    @forelse ($topProducts as $p)
      <div class="flex justify-between py-2 border-b border-white/5 text-sm"><span class="text-stone-300">{{ $p->name }} <span class="text-stone-600">×{{ $p->qty }}</span></span><span class="text-gold-soft">₹{{ number_format($p->revenue,2) }}</span></div>
    @empty<p class="text-stone-500 text-sm">No product sales yet.</p>@endforelse
  </div>

  <!-- Top customers -->
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-4">Top Customers</h2>
    @forelse ($topCustomers as $c)
      <div class="flex justify-between py-2 border-b border-white/5 text-sm"><span class="text-stone-300">{{ $c->customer->name ?? '—' }} <span class="text-stone-600">· {{ $c->visits }} visits</span></span><span class="text-gold-soft">₹{{ number_format($c->spent,2) }}</span></div>
    @empty<p class="text-stone-500 text-sm">No data yet.</p>@endforelse
  </div>
</div>
@endsection
