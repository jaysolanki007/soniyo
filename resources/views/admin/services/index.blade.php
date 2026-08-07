@extends('admin.layout')
@section('title', 'Services')
@section('subtitle', 'Service menu shown on your website')

@section('actions')
<a href="{{ route('admin.services.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Service</a>
@endsection

@section('content')
<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr><th class="px-5 py-3">Service</th><th class="px-5 py-3">Category</th><th class="px-5 py-3">Duration</th><th class="px-5 py-3">Price</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($services as $s)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3"><div class="flex items-center gap-2">@if($s->is_featured)<span title="Featured" class="text-gold">★</span>@endif<span class="text-stone-100">{{ $s->name }}</span></div></td>
          <td class="px-5 py-3 text-stone-400">{{ $s->category->name ?? '—' }}</td>
          <td class="px-5 py-3 text-stone-400">{{ $s->duration_min }} min</td>
          <td class="px-5 py-3 text-gold-soft">₹{{ number_format($s->price,0) }}</td>
          <td class="px-5 py-3">@if($s->is_active)<span class="text-emerald-400 text-xs">Active</span>@else<span class="text-stone-500 text-xs">Hidden</span>@endif</td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.services.edit', $s) }}" class="text-stone-400 hover:text-gold">Edit</a>
            <form action="{{ route('admin.services.destroy', $s) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete service?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-5 py-10 text-center text-stone-500">No services yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $services->links() }}</div>
@endsection
