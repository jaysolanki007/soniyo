@extends('admin.layout')
@section('title', $offer->exists ? 'Edit Offer' : 'New Offer')

@section('content')
@php $o=$offer; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $o->exists ? route('admin.offers.update',$o) : route('admin.offers.store') }}" enctype="multipart/form-data" class="max-w-3xl">
  @csrf
  @if ($o->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Title *</label><input name="title" value="{{ old('title',$o->title) }}" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Coupon Code</label><input name="code" value="{{ old('code',$o->code) }}" placeholder="WELCOME20" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Discount Type</label>
      <select name="discount_type" class="{{ $inp }}">
        <option value="percent" @selected(old('discount_type',$o->discount_type)==='percent')>Percent (%)</option>
        <option value="fixed" @selected(old('discount_type',$o->discount_type)==='fixed')>Fixed (₹)</option>
      </select>
    </div>
    <div><label class="{{ $lbl }}">Discount Value</label><input name="discount_value" type="number" step="0.01" value="{{ old('discount_value',$o->discount_value ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Usage Limit</label><input name="usage_limit" type="number" value="{{ old('usage_limit',$o->usage_limit) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Valid From</label><input name="valid_from" type="date" value="{{ old('valid_from',optional($o->valid_from)->format('Y-m-d')) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Valid To</label><input name="valid_to" type="date" value="{{ old('valid_to',optional($o->valid_to)->format('Y-m-d')) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Image URL</label><input name="image_url" value="{{ old('image_url', \Illuminate\Support\Str::startsWith($o->image,'http') ? $o->image : '') }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">…or Upload Image</label><input name="image_file" type="file" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900"></div>
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Description</label><textarea name="description" rows="2" class="{{ $inp }}">{{ old('description',$o->description) }}</textarea></div>
    <div class="flex items-end gap-6">
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$o->is_featured)) class="rounded bg-transparent border-white/20 text-gold"> Featured</label>
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$o->exists ? $o->is_active : true)) class="rounded bg-transparent border-white/20 text-gold"> Active</label>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Offer</button>
    <a href="{{ route('admin.offers.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
