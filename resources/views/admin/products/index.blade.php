@extends('admin.layout')
@section('title', 'Products & Inventory')
@section('subtitle', 'Retail stock & supplies')

@section('actions')
<a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Product</a>
@endsection

@section('content')
<div class="flex flex-wrap items-center gap-3 mb-5">
  <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg text-sm border {{ !request('low') ? 'border-gold text-gold' : 'border-white/10 text-stone-400' }}">All Products</a>
  <a href="{{ route('admin.products.index', ['low'=>1]) }}" class="px-4 py-2 rounded-lg text-sm border {{ request('low') ? 'border-gold text-gold' : 'border-white/10 text-stone-400' }}">
    ⚠ Low Stock @if($lowCount)<span class="ml-1 text-rose-300">({{ $lowCount }})</span>@endif
  </a>
</div>

<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr><th class="px-5 py-3">Product</th><th class="px-5 py-3">SKU</th><th class="px-5 py-3">Supplier</th><th class="px-5 py-3">Cost / Sell</th><th class="px-5 py-3">Stock</th><th class="px-5 py-3 text-right">Actions</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @forelse ($products as $p)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3"><div class="text-stone-100">{{ $p->name }}</div><div class="text-xs text-stone-500">{{ $p->category }}</div></td>
          <td class="px-5 py-3 text-stone-500 text-xs">{{ $p->sku ?: '—' }}</td>
          <td class="px-5 py-3 text-stone-400">{{ $p->supplier->name ?? '—' }}</td>
          <td class="px-5 py-3 text-stone-400">₹{{ number_format($p->cost_price,0) }} / <span class="text-gold-soft">₹{{ number_format($p->selling_price,0) }}</span></td>
          <td class="px-5 py-3">
            <span class="{{ $p->is_low_stock ? 'text-rose-300' : 'text-stone-200' }}">{{ $p->stock_qty }}</span>
            @if($p->is_low_stock)<span class="ml-1 text-[9px] uppercase bg-rose-500/15 text-rose-300 px-1.5 py-0.5 rounded">low</span>@endif
          </td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <form action="{{ route('admin.products.stock', $p) }}" method="POST" class="inline-flex items-center gap-1 mr-3">@csrf
              <input type="hidden" name="type" value="in">
              <input type="number" name="qty" value="1" min="1" class="w-14 px-2 py-1 rounded bg-ink-900 border border-white/10 text-stone-100 text-xs">
              <button class="text-emerald-400 hover:text-emerald-300 text-xs">+Stock</button>
            </form>
            <a href="{{ route('admin.products.edit', $p) }}" class="text-stone-400 hover:text-gold">Edit</a>
            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete product?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-5 py-10 text-center text-stone-500">No products yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
