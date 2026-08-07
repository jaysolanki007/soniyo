@extends('admin.layout')
@section('title', 'Dashboard')
@section('subtitle', 'Welcome back — here is what is happening today.')

@section('content')
@php
  $cards = [
    ['Appointments Today', $stats['appointments_today'], '📅', 'from-amber-500/20'],
    ['Revenue (Month)', '₹'.number_format($stats['revenue_month'], 0), '💰', 'from-emerald-500/20'],
    ['Total Customers', $stats['customers'], '👥', 'from-sky-500/20'],
    ['Pending Bookings', $stats['pending'], '⏳', 'from-rose-500/20'],
    ['Active Staff', $stats['staff'], '💇', 'from-violet-500/20'],
    ['Low Stock Items', $stats['low_stock'], '📦', 'from-rose-500/20'],
  ];
@endphp

<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
  @foreach ($cards as $c)
    <div class="rounded-xl border border-white/5 bg-gradient-to-br {{ $c[3] }} to-ink-800 p-5">
      <div class="text-2xl mb-3">{{ $c[2] }}</div>
      <div class="serif text-3xl text-stone-100">{{ $c[1] }}</div>
      <div class="text-xs text-stone-400 mt-1">{{ $c[0] }}</div>
    </div>
  @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
  <!-- Upcoming appointments -->
  <div class="lg:col-span-2 rounded-xl border border-white/5 bg-ink-800">
    <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
      <h2 class="serif text-xl text-stone-100">Upcoming Appointments</h2>
      <a href="{{ route('admin.appointments.index') }}" class="text-xs text-gold hover:underline">View all →</a>
    </div>
    <div class="divide-y divide-white/5">
      @forelse ($upcoming as $a)
        <div class="flex items-center gap-4 px-5 py-3">
          <div class="w-11 h-11 rounded-lg bg-ink-600 flex flex-col items-center justify-center text-center shrink-0">
            <span class="text-gold-soft text-sm leading-none">{{ $a->scheduled_at->format('d') }}</span>
            <span class="text-[9px] text-stone-500 uppercase">{{ $a->scheduled_at->format('M') }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-stone-100 truncate">{{ $a->customer_name }}</div>
            <div class="text-xs text-stone-500">{{ $a->service->name ?? 'Service' }} · {{ $a->scheduled_at->format('g:i A') }} · {{ $a->staff->name ?? 'Any stylist' }}</div>
          </div>
          <span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 text-gold-soft">{{ \App\Models\Appointment::STATUSES[$a->status] ?? $a->status }}</span>
        </div>
      @empty
        <p class="px-5 py-8 text-center text-stone-500 text-sm">No upcoming appointments.</p>
      @endforelse
    </div>
  </div>

  <div class="space-y-6">
    <!-- Status breakdown -->
    <div class="rounded-xl border border-white/5 bg-ink-800 p-5">
      <h2 class="serif text-xl text-stone-100 mb-4">Appointments by Status</h2>
      <div class="space-y-2">
        @foreach (\App\Models\Appointment::STATUSES as $key => $label)
          <div class="flex items-center justify-between text-sm">
            <span class="text-stone-400">{{ $label }}</span>
            <span class="text-gold-soft font-medium">{{ $statusCounts[$key] ?? 0 }}</span>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Birthdays -->
    <div class="rounded-xl border border-white/5 bg-ink-800 p-5">
      <h2 class="serif text-xl text-stone-100 mb-3">🎂 Upcoming Birthdays</h2>
      @forelse ($birthdays as $b)
        <div class="flex items-center justify-between text-sm py-1.5">
          <span class="text-stone-300">{{ $b->name }}</span>
          <span class="text-stone-500">{{ \Illuminate\Support\Carbon::parse($b->dob)->format('M d') }}</span>
        </div>
      @empty
        <p class="text-stone-500 text-sm">No birthdays in the next 30 days.</p>
      @endforelse
    </div>
  </div>
</div>

<!-- Recent customers -->
<div class="rounded-xl border border-white/5 bg-ink-800 mt-6">
  <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
    <h2 class="serif text-xl text-stone-100">New Customers</h2>
    <a href="{{ route('admin.customers.index') }}" class="text-xs text-gold hover:underline">View all →</a>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-px bg-white/5">
    @forelse ($recentCustomers as $c)
      <a href="{{ route('admin.customers.show', $c) }}" class="bg-ink-800 p-4 hover:bg-ink-700 transition">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold-soft to-gold-deep text-ink-900 font-semibold flex items-center justify-center mb-2">{{ strtoupper(substr($c->name,0,1)) }}</div>
        <div class="text-stone-100 text-sm truncate">{{ $c->name }}</div>
        <div class="text-xs text-stone-500">{{ ucfirst($c->membership) }} member</div>
      </a>
    @empty
      <p class="bg-ink-800 p-8 text-center text-stone-500 text-sm col-span-full">No customers yet.</p>
    @endforelse
  </div>
</div>
@endsection
