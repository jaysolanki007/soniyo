@extends('admin.layout')
@section('title', $service->exists ? 'Edit Service' : 'New Service')

@section('content')
@php $s=$service; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $s->exists ? route('admin.services.update',$s) : route('admin.services.store') }}" enctype="multipart/form-data" class="max-w-3xl">
  @csrf
  @if ($s->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Service Name *</label><input name="name" value="{{ old('name',$s->name) }}" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Category</label>
      <select name="category_id" class="{{ $inp }}"><option value="">—</option>
        @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id',$s->category_id)==$c->id)>{{ $c->name }}</option>@endforeach
      </select>
    </div>
    <div><label class="{{ $lbl }}">Duration (min)</label><input name="duration_min" type="number" value="{{ old('duration_min',$s->duration_min ?? 45) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Price (₹)</label><input name="price" type="number" step="0.01" value="{{ old('price',$s->price ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Tax (%)</label><input name="tax_percent" type="number" step="0.01" value="{{ old('tax_percent',$s->tax_percent ?? 0) }}" class="{{ $inp }}"></div>
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Description</label><textarea name="description" rows="3" class="{{ $inp }}">{{ old('description',$s->description) }}</textarea></div>
    <div><label class="{{ $lbl }}">Image URL</label><input name="image_url" value="{{ old('image_url', \Illuminate\Support\Str::startsWith($s->image,'http') ? $s->image : '') }}" placeholder="https://…" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">…or Upload Image</label><input name="image_file" type="file" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900"></div>
    <div><label class="{{ $lbl }}">Sort Order</label><input name="sort_order" type="number" value="{{ old('sort_order',$s->sort_order ?? 0) }}" class="{{ $inp }}"></div>
    <div class="flex items-end gap-6">
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$s->is_featured)) class="rounded bg-transparent border-white/20 text-gold"> Featured</label>
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$s->exists ? $s->is_active : true)) class="rounded bg-transparent border-white/20 text-gold"> Active</label>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Service</button>
    <a href="{{ route('admin.services.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
