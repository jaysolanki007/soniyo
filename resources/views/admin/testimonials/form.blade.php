@extends('admin.layout')
@section('title', $testimonial->exists ? 'Edit Review' : 'New Review')

@section('content')
@php $t=$testimonial; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $t->exists ? route('admin.testimonials.update',$t) : route('admin.testimonials.store') }}" enctype="multipart/form-data" class="max-w-2xl">
  @csrf
  @if ($t->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div><label class="{{ $lbl }}">Customer Name *</label><input name="customer_name" value="{{ old('customer_name',$t->customer_name) }}" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Role / Tag</label><input name="role" value="{{ old('role',$t->role) }}" placeholder="Bridal Client" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Rating</label>
      <select name="rating" class="{{ $inp }}">@for($i=5;$i>=1;$i--)<option value="{{ $i }}" @selected(old('rating',$t->rating ?? 5)==$i)>{{ $i }} ★</option>@endfor</select>
    </div>
    <div><label class="{{ $lbl }}">Sort Order</label><input name="sort_order" type="number" value="{{ old('sort_order',$t->sort_order ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Photo URL</label><input name="photo_url" value="{{ old('photo_url', \Illuminate\Support\Str::startsWith($t->photo,'http') ? $t->photo : '') }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">…or Upload Photo</label><input name="photo_file" type="file" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900"></div>
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Quote *</label><textarea name="quote" rows="4" required class="{{ $inp }}">{{ old('quote',$t->quote) }}</textarea></div>
    <div class="sm:col-span-2"><label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_public" value="1" @checked(old('is_public',$t->exists ? $t->is_public : true)) class="rounded bg-transparent border-white/20 text-gold"> Show on website</label></div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Review</button>
    <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
