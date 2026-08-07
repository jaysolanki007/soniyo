@extends('admin.layout')
@section('title', 'Appointments')
@section('subtitle', 'Manage bookings & schedule')

@section('actions')
<a href="{{ route('admin.appointments.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Booking</a>
@endsection

@section('content')
<div class="flex flex-wrap gap-2 mb-5">
  <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 rounded-lg text-sm border {{ !$status ? 'border-gold text-gold' : 'border-white/10 text-stone-400' }}">All</a>
  @foreach ($statuses as $k => $label)
    <a href="{{ route('admin.appointments.index', ['status'=>$k]) }}" class="px-4 py-2 rounded-lg text-sm border {{ $status===$k ? 'border-gold text-gold' : 'border-white/10 text-stone-400' }}">{{ $label }}</a>
  @endforeach
</div>

<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
        <tr><th class="px-5 py-3">Ref</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Service</th><th class="px-5 py-3">Stylist</th><th class="px-5 py-3">When</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr>
      </thead>
      <tbody class="divide-y divide-white/5">
        @forelse ($appointments as $a)
          <tr class="hover:bg-white/5">
            <td class="px-5 py-3 text-stone-500 text-xs">{{ $a->reference }}</td>
            <td class="px-5 py-3"><div class="text-stone-100">{{ $a->customer_name }}</div><div class="text-xs text-stone-500">{{ $a->customer_phone }}</div></td>
            <td class="px-5 py-3 text-stone-300">{{ $a->service->name ?? '—' }}</td>
            <td class="px-5 py-3 text-stone-400">{{ $a->staff->name ?? '—' }}</td>
            <td class="px-5 py-3 text-stone-400">{{ $a->scheduled_at->format('M d, g:i A') }}</td>
            <td class="px-5 py-3"><span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 text-gold-soft">{{ $statuses[$a->status] ?? $a->status }}</span></td>
            <td class="px-5 py-3 text-right whitespace-nowrap">
              <a href="{{ route('admin.appointments.edit', $a) }}" class="text-stone-400 hover:text-gold">Edit</a>
              <form action="{{ route('admin.appointments.destroy', $a) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete this appointment?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-5 py-10 text-center text-stone-500">No appointments.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $appointments->links() }}</div>
@endsection
