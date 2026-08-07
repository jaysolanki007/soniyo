@extends('admin.layout')
@section('title', $item->exists ? 'Edit Gallery Image' : 'Add Gallery Image')

@section('content')
@php $g=$item; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $g->exists ? route('admin.gallery.update',$g) : route('admin.gallery.store') }}" enctype="multipart/form-data" class="max-w-2xl">
  @csrf
  @if ($g->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Title</label><input name="title" value="{{ old('title',$g->title) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Category</label>
      <select name="category" class="{{ $inp }}">
        @foreach (['general','haircuts','hair_color','bridal','makeup','nail_art'] as $c)
          <option value="{{ $c }}" @selected(old('category',$g->category)===$c)>{{ ucwords(str_replace('_',' ',$c)) }}</option>
        @endforeach
      </select>
    </div>
    <div><label class="{{ $lbl }}">Sort Order</label><input name="sort_order" type="number" value="{{ old('sort_order',$g->sort_order ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Image URL</label><input name="image_url" value="{{ old('image_url', \Illuminate\Support\Str::startsWith($g->image,'http') ? $g->image : '') }}" placeholder="https://…" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">…or Upload Image</label><input name="image_file" type="file" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900"></div>
    <div class="sm:col-span-2">
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_public" value="1" @checked(old('is_public',$g->exists ? $g->is_public : true)) class="rounded bg-transparent border-white/20 text-gold"> Show on website</label>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Image</button>
    <a href="{{ route('admin.gallery.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
