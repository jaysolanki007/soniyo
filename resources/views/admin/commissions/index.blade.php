@extends('admin.layout')
@section('title', 'Commissions')
@section('subtitle', 'What each stylist earned from the revenue they generated')

@section('actions')
<form method="GET" class="flex items-center gap-2">
  <input type="month" name="period" value="{{ $period }}" class="px-3 py-2 rounded-lg bg-ink-900 border border-white/10 text-stone-100 text-sm">
  <button class="px-4 py-2 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">View</button>
</form>
@endsection

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-stone-100">₹{{ number_format($totals['revenue'],0) }}</div><div class="text-xs text-stone-500">Revenue Generated · {{ $label }}</div></div>
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-gold-soft">₹{{ number_format($totals['commission'],0) }}</div><div class="text-xs text-stone-500">Commission</div></div>
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-emerald-300">₹{{ number_format($totals['bonus'],0) }}</div><div class="text-xs text-stone-500">Target Bonuses</div></div>
  <div class="rounded-xl border border-white/5 bg-gradient-to-br from-gold/15 to-ink-800 p-5"><div class="serif text-2xl text-gold-soft">₹{{ number_format($totals['earning'],0) }}</div><div class="text-xs text-stone-500">Total Payable</div></div>
</div>

<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr>
        <th class="px-5 py-3">Stylist</th>
        <th class="px-5 py-3">Plan</th>
        <th class="px-5 py-3 text-right">Service ₹</th>
        <th class="px-5 py-3 text-right">Product ₹</th>
        <th class="px-5 py-3 text-right">Target</th>
        <th class="px-5 py-3 text-right">Commission</th>
        <th class="px-5 py-3 text-right">Bonus</th>
        <th class="px-5 py-3 text-right">Total Earned</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($rows as $r)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3"><div class="text-stone-100">{{ $r['staff']->name }}</div><div class="text-xs text-stone-500">{{ $r['staff']->title }}</div></td>
          <td class="px-5 py-3 text-stone-400 text-xs">
            @if ($r['staff']->commission_type === 'split')
              Split · {{ rtrim(rtrim($r['staff']->commission_percent,'0'),'.') }}% svc / {{ rtrim(rtrim($r['staff']->product_commission_percent,'0'),'.') }}% prod
            @else
              Flat · {{ rtrim(rtrim($r['staff']->commission_percent,'0'),'.') }}%
            @endif
          </td>
          <td class="px-5 py-3 text-right text-stone-300">₹{{ number_format($r['service_revenue'],0) }}</td>
          <td class="px-5 py-3 text-right text-stone-300">₹{{ number_format($r['product_revenue'],0) }}</td>
          <td class="px-5 py-3 text-right">
            @if ($r['staff']->target_amount > 0)
              <span class="{{ $r['target_met'] ? 'text-emerald-400' : 'text-stone-500' }}">{{ $r['target_met'] ? '✓ met' : '₹'.number_format($r['staff']->target_amount,0) }}</span>
            @else <span class="text-stone-600">—</span> @endif
          </td>
          <td class="px-5 py-3 text-right text-gold-soft">₹{{ number_format($r['commission'],0) }}</td>
          <td class="px-5 py-3 text-right text-emerald-300">{{ $r['target_bonus'] > 0 ? '₹'.number_format($r['target_bonus'],0) : '—' }}</td>
          <td class="px-5 py-3 text-right text-stone-100 font-medium">₹{{ number_format($r['total_earning'],0) }}</td>
        </tr>
      @empty
        <tr><td colspan="8" class="px-5 py-10 text-center text-stone-500">No active staff.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<p class="text-xs text-stone-500 mt-4">Commission is calculated from the invoice lines each stylist is assigned at the POS. Set each stylist's rates, salary &amp; monthly target in <a href="{{ route('admin.staff.index') }}" class="text-gold">Team / Staff</a>. Turn these figures into payslips in <a href="{{ route('admin.payroll.index') }}" class="text-gold">Payroll</a>.</p>
@endsection
