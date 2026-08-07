@extends('admin.layout')
@section('title', 'Payroll')
@section('subtitle', 'Salary + commission payslips')

@section('actions')
<form method="GET" class="flex items-center gap-2">
  <input type="month" name="period" value="{{ $period }}" class="px-3 py-2 rounded-lg bg-ink-900 border border-white/10 text-stone-100 text-sm">
  <button class="px-4 py-2 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">View</button>
</form>
<form method="POST" action="{{ route('admin.payroll.generate') }}" onsubmit="return confirm('Generate / refresh payslips for {{ $label }}? Paid payslips are kept untouched.')">
  @csrf
  <input type="hidden" name="period" value="{{ $period }}">
  <button class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">⚙ Generate {{ $label }}</button>
</form>
@endsection

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-gold-soft">₹{{ number_format($totals['commission'],0) }}</div><div class="text-xs text-stone-500">Commission · {{ $label }}</div></div>
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-stone-100">₹{{ number_format($totals['gross'],0) }}</div><div class="text-xs text-stone-500">Gross Payroll</div></div>
  <div class="rounded-xl border border-white/5 bg-gradient-to-br from-gold/15 to-ink-800 p-5"><div class="serif text-2xl text-gold-soft">₹{{ number_format($totals['net'],0) }}</div><div class="text-xs text-stone-500">Net Payable</div></div>
  <div class="rounded-xl border border-white/5 bg-ink-800 p-5"><div class="serif text-2xl text-emerald-300">₹{{ number_format($totals['paid'],0) }}</div><div class="text-xs text-stone-500">Already Paid</div></div>
</div>

<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr>
        <th class="px-5 py-3">Staff</th>
        <th class="px-5 py-3 text-right">Base</th>
        <th class="px-5 py-3 text-right">Commission</th>
        <th class="px-5 py-3 text-right">Bonus</th>
        <th class="px-5 py-3 text-right">Incentive</th>
        <th class="px-5 py-3 text-right">Deduction</th>
        <th class="px-5 py-3 text-right">Net Pay</th>
        <th class="px-5 py-3">Status</th>
        <th class="px-5 py-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($payslips as $p)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3"><div class="text-stone-100">{{ $p->staff->name ?? '—' }}</div><div class="text-xs text-stone-500">{{ $p->staff->title }}</div></td>
          <td class="px-5 py-3 text-right text-stone-300">₹{{ number_format($p->base_salary,0) }}</td>
          <td class="px-5 py-3 text-right text-gold-soft">₹{{ number_format($p->commission_amount,0) }}</td>
          <td class="px-5 py-3 text-right text-emerald-300">{{ $p->target_bonus>0 ? '₹'.number_format($p->target_bonus,0) : '—' }}</td>
          <td class="px-5 py-3 text-right text-stone-300">₹{{ number_format($p->incentive,0) }}</td>
          <td class="px-5 py-3 text-right text-rose-300">₹{{ number_format($p->deduction,0) }}</td>
          <td class="px-5 py-3 text-right text-stone-100 font-medium">₹{{ number_format($p->net,0) }}</td>
          <td class="px-5 py-3">@if($p->status==='paid')<span class="text-emerald-400 text-xs">✓ Paid</span>@else<span class="text-gold-soft text-xs">Draft</span>@endif</td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.payroll.show', $p) }}" class="text-stone-400 hover:text-gold">Slip</a>
            <a href="{{ route('admin.payroll.edit', $p) }}" class="text-stone-400 hover:text-gold ml-3">Edit</a>
            @if($p->status!=='paid')
              <form action="{{ route('admin.payroll.paid', $p) }}" method="POST" class="inline ml-3">@csrf @method('PATCH')<button class="text-emerald-400 hover:text-emerald-300">Mark Paid</button></form>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="9" class="px-5 py-10 text-center text-stone-500">No payslips for {{ $label }} yet. Click <span class="text-gold">Generate {{ $label }}</span> to create them for all {{ $staffCount }} active staff.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<p class="text-xs text-stone-500 mt-4">Commission is pulled automatically from POS sales. After generating, use <em>Edit</em> to add incentives or deductions, then <em>Mark Paid</em>.</p>
@endsection
