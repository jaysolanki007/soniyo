@extends('admin.layout')
@section('title', $product->exists ? 'Edit Product' : 'New Product')

@section('content')
@php $p=$product; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $p->exists ? route('admin.products.update',$p) : route('admin.products.store') }}" enctype="multipart/form-data" class="max-w-3xl">
  @csrf
  @if ($p->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Name *</label><input name="name" value="{{ old('name',$p->name) }}" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">SKU</label><input name="sku" value="{{ old('sku',$p->sku) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Barcode</label><input name="barcode" value="{{ old('barcode',$p->barcode) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Category</label><input name="category" value="{{ old('category',$p->category) }}" placeholder="Shampoo, Oil…" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Supplier</label>
      <select name="supplier_id" class="{{ $inp }}"><option value="">—</option>
        @foreach ($suppliers as $sup)<option value="{{ $sup->id }}" @selected(old('supplier_id',$p->supplier_id)==$sup->id)>{{ $sup->name }}</option>@endforeach
      </select>
    </div>
    <div><label class="{{ $lbl }}">Cost Price (₹)</label><input name="cost_price" type="number" step="0.01" value="{{ old('cost_price',$p->cost_price ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Selling Price (₹)</label><input name="selling_price" type="number" step="0.01" value="{{ old('selling_price',$p->selling_price ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">{{ $p->exists ? 'Stock Qty (use +Stock to adjust)' : 'Opening Stock Qty' }}</label><input name="stock_qty" type="number" value="{{ old('stock_qty',$p->stock_qty ?? 0) }}" class="{{ $inp }}" {{ $p->exists ? 'readonly' : '' }}></div>
    <div><label class="{{ $lbl }}">Low Stock Alert At</label><input name="low_stock_threshold" type="number" value="{{ old('low_stock_threshold',$p->low_stock_threshold ?? 5) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Expiry Date</label><input name="expiry_date" type="date" value="{{ old('expiry_date',optional($p->expiry_date)->format('Y-m-d')) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Image URL</label><input name="image_url" value="{{ old('image_url', \Illuminate\Support\Str::startsWith($p->image,'http') ? $p->image : '') }}" class="{{ $inp }}"></div>
    <div class="flex items-end">
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$p->exists ? $p->is_active : true)) class="rounded bg-transparent border-white/20 text-gold"> Active</label>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Product</button>
    <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
