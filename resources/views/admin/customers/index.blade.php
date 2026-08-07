@extends('admin.layout')
@section('title', 'Customers')
@section('subtitle', 'Your complete client directory (CRM)')

@section('actions')
<a href="{{ route('admin.customers.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Customer</a>
@endsection

@section('content')
<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <div class="p-4 border-b border-white/5">
    <form method="GET" class="flex gap-2">
      <input name="q" value="{{ $q }}" placeholder="Search name, phone or email…"
             class="flex-1 px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100 text-sm">
      <button class="px-5 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Search</button>
    </form>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
        <tr><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Contact</th><th class="px-5 py-3">Membership</th><th class="px-5 py-3">Points</th><th class="px-5 py-3">Last Visit</th><th class="px-5 py-3 text-right">Actions</th></tr>
      </thead>
      <tbody class="divide-y divide-white/5">
        @forelse ($customers as $c)
          <tr class="hover:bg-white/5">
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-soft to-gold-deep text-ink-900 font-semibold flex items-center justify-center">{{ strtoupper(substr($c->name,0,1)) }}</div>
                <div><div class="text-stone-100">{{ $c->name }}</div><div class="text-xs text-stone-500">{{ ucfirst($c->gender ?? '—') }}</div></div>
              </div>
            </td>
            <td class="px-5 py-3 text-stone-400">{{ $c->phone }}<div class="text-xs text-stone-600">{{ $c->email }}</div></td>
            <td class="px-5 py-3"><span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 text-gold-soft">{{ ucfirst($c->membership) }}</span></td>
            <td class="px-5 py-3 text-stone-300">{{ $c->loyalty_points }}</td>
            <td class="px-5 py-3 text-stone-500">{{ $c->last_visit_at?->format('M d, Y') ?? '—' }}</td>
            <td class="px-5 py-3 text-right whitespace-nowrap">
              <a href="{{ route('admin.customers.show', $c) }}" class="text-stone-400 hover:text-gold">View</a>
              <a href="{{ route('admin.customers.edit', $c) }}" class="text-stone-400 hover:text-gold ml-3">Edit</a>
              <form action="{{ route('admin.customers.destroy', $c) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete this customer?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-5 py-10 text-center text-stone-500">No customers found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection
