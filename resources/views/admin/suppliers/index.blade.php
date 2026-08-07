@extends('admin.layout')
@section('title', 'Suppliers')
@section('subtitle', 'Vendor directory & balances')

@section('actions')
<a href="{{ route('admin.suppliers.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Supplier</a>
@endsection

@section('content')
<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr><th class="px-5 py-3">Supplier</th><th class="px-5 py-3">Contact</th><th class="px-5 py-3">Products</th><th class="px-5 py-3">Outstanding</th><th class="px-5 py-3 text-right">Actions</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($suppliers as $s)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3"><div class="text-stone-100">{{ $s->name }}</div><div class="text-xs text-stone-500">{{ $s->company }}</div></td>
          <td class="px-5 py-3 text-stone-400">{{ $s->phone }}<div class="text-xs text-stone-600">{{ $s->email }}</div></td>
          <td class="px-5 py-3 text-stone-300">{{ $s->products_count }}</td>
          <td class="px-5 py-3 {{ $s->outstanding_balance > 0 ? 'text-rose-300' : 'text-stone-400' }}">₹{{ number_format($s->outstanding_balance,2) }}</td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.suppliers.edit', $s) }}" class="text-stone-400 hover:text-gold">Edit</a>
            <form action="{{ route('admin.suppliers.destroy', $s) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete supplier?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="px-5 py-10 text-center text-stone-500">No suppliers yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $suppliers->links() }}</div>
@endsection
