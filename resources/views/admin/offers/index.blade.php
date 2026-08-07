@extends('admin.layout')
@section('title', 'Offers & Coupons')
@section('subtitle', 'Promotions shown on your website')

@section('actions')
<a href="{{ route('admin.offers.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Offer</a>
@endsection

@section('content')
<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr><th class="px-5 py-3">Offer</th><th class="px-5 py-3">Code</th><th class="px-5 py-3">Discount</th><th class="px-5 py-3">Valid Till</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($offers as $o)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3"><div class="flex items-center gap-2">@if($o->is_featured)<span class="text-gold">★</span>@endif<span class="text-stone-100">{{ $o->title }}</span></div></td>
          <td class="px-5 py-3"><span class="font-mono text-gold-soft">{{ $o->code ?: '—' }}</span></td>
          <td class="px-5 py-3 text-stone-300">{{ $o->discount_type==='percent' ? $o->discount_value.'%' : '₹'.$o->discount_value }}</td>
          <td class="px-5 py-3 text-stone-400">{{ $o->valid_to?->format('M d, Y') ?? '—' }}</td>
          <td class="px-5 py-3">@if($o->is_active)<span class="text-emerald-400 text-xs">Active</span>@else<span class="text-stone-500 text-xs">Inactive</span>@endif</td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.offers.edit', $o) }}" class="text-stone-400 hover:text-gold">Edit</a>
            <form action="{{ route('admin.offers.destroy', $o) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete offer?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-5 py-10 text-center text-stone-500">No offers yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $offers->links() }}</div>
@endsection
