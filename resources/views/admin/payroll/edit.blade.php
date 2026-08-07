@extends('admin.layout')
@section('title', 'Edit Payslip')
@section('subtitle', $slip->staff->name.' · '.$slip->period_label)

@section('content')
@php $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<div class="max-w-2xl grid gap-6">
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <h2 class="serif text-xl text-stone-100 mb-4">Earnings (auto from sales)</h2>
    <dl class="grid grid-cols-2 gap-3 text-sm">
      <div class="flex justify-between"><dt class="text-stone-500">Service revenue</dt><dd class="text-stone-200">₹{{ number_format($slip->service_revenue,0) }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Product revenue</dt><dd class="text-stone-200">₹{{ number_format($slip->product_revenue,0) }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Base salary</dt><dd class="text-stone-200">₹{{ number_format($slip->base_salary,0) }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Commission</dt><dd class="text-gold-soft">₹{{ number_format($slip->commission_amount,0) }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Target bonus</dt><dd class="text-emerald-300">₹{{ number_format($slip->target_bonus,0) }}</dd></div>
    </dl>
  </div>

  <form method="POST" action="{{ route('admin.payroll.update', $slip) }}" class="rounded-xl border border-white/5 bg-ink-800 p-6 space-y-5">
    @csrf @method('PUT')
    <h2 class="serif text-xl text-stone-100">Adjustments</h2>
    <div class="grid sm:grid-cols-2 gap-4">
      <div><label class="{{ $lbl }}">Incentive / Tips (₹)</label><input name="incentive" type="number" step="0.01" value="{{ old('incentive',$slip->incentive) }}" class="{{ $inp }}"></div>
      <div><label class="{{ $lbl }}">Deduction (₹)</label><input name="deduction" type="number" step="0.01" value="{{ old('deduction',$slip->deduction) }}" class="{{ $inp }}"></div>
    </div>
    <div><label class="{{ $lbl }}">Notes</label><textarea name="notes" rows="2" class="{{ $inp }}">{{ old('notes',$slip->notes) }}</textarea></div>
    <p class="text-sm text-stone-400">Net pay updates automatically: Base + Commission + Bonus + Incentive − Deduction.</p>
    <div class="flex gap-3">
      <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Payslip</button>
      <a href="{{ route('admin.payroll.index', ['period'=>$slip->period]) }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
    </div>
  </form>
</div>
@endsection
