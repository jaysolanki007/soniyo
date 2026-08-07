@extends('admin.layout')
@section('title', $customer->name)
@section('subtitle', 'Customer profile & history')

@section('actions')
<a href="{{ route('admin.customers.edit', $customer) }}" class="px-5 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Edit</a>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
  <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
    <div class="flex items-center gap-4 mb-5">
      <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gold-soft to-gold-deep text-ink-900 text-2xl font-semibold flex items-center justify-center">{{ strtoupper(substr($customer->name,0,1)) }}</div>
      <div>
        <h2 class="serif text-2xl text-stone-100">{{ $customer->name }}</h2>
        <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 text-gold-soft">{{ ucfirst($customer->membership) }} member</span>
      </div>
    </div>
    <dl class="space-y-3 text-sm">
      <div class="flex justify-between"><dt class="text-stone-500">Phone</dt><dd class="text-stone-200">{{ $customer->phone ?? '—' }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Email</dt><dd class="text-stone-200">{{ $customer->email ?? '—' }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">DOB</dt><dd class="text-stone-200">{{ $customer->dob?->format('M d, Y') ?? '—' }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Preferred stylist</dt><dd class="text-stone-200">{{ $customer->preferredStylist->name ?? '—' }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Loyalty points</dt><dd class="text-gold-soft">{{ $customer->loyalty_points }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Total spent</dt><dd class="text-stone-200">₹{{ number_format($customer->total_spent,2) }}</dd></div>
      <div class="flex justify-between"><dt class="text-stone-500">Visits</dt><dd class="text-stone-200">{{ $customer->visit_count }}</dd></div>
    </dl>
    @if ($customer->allergies)
      <div class="mt-4 p-3 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-200 text-sm">⚠ Allergies: {{ $customer->allergies }}</div>
    @endif
    @if ($customer->notes)
      <div class="mt-3 text-sm text-stone-400"><span class="text-stone-500">Notes:</span> {{ $customer->notes }}</div>
    @endif
  </div>

  <div class="lg:col-span-2 rounded-xl border border-white/5 bg-ink-800">
    <div class="px-5 py-4 border-b border-white/5"><h2 class="serif text-xl text-stone-100">Appointment History</h2></div>
    <div class="divide-y divide-white/5">
      @forelse ($customer->appointments->sortByDesc('scheduled_at') as $a)
        <div class="flex items-center gap-4 px-5 py-3">
          <div class="flex-1"><div class="text-stone-100">{{ $a->service->name ?? 'Service' }}</div><div class="text-xs text-stone-500">{{ $a->scheduled_at->format('M d, Y · g:i A') }} · {{ $a->staff->name ?? '—' }}</div></div>
          <span class="text-stone-300 text-sm">₹{{ number_format($a->price,0) }}</span>
          <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 text-gold-soft">{{ \App\Models\Appointment::STATUSES[$a->status] ?? $a->status }}</span>
        </div>
      @empty
        <p class="px-5 py-10 text-center text-stone-500 text-sm">No appointments yet.</p>
      @endforelse
    </div>
  </div>
</div>

<a href="{{ route('admin.customers.index') }}" class="inline-block mt-5 text-sm text-stone-400 hover:text-gold">← Back to customers</a>
@endsection
