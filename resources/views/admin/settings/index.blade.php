@extends('admin.layout')
@section('title', 'Website Content')
@section('subtitle', 'Edit the text & images on your public site')

@section('content')
@php $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6">
  @csrf
  @forelse ($groups as $group => $items)
    <div class="rounded-xl border border-white/5 bg-ink-800 p-6">
      <h2 class="serif text-xl text-stone-100 mb-5 capitalize">{{ $group }}</h2>
      <div class="grid sm:grid-cols-2 gap-5">
        @foreach ($items as $s)
          <div class="{{ $s->type==='textarea' ? 'sm:col-span-2' : '' }}">
            <label class="{{ $lbl }}">{{ $s->label ?: $s->key }}</label>
            @if ($s->type==='textarea')
              <textarea name="settings[{{ $s->key }}]" rows="3" class="{{ $inp }}">{{ old('settings.'.$s->key, $s->value) }}</textarea>
            @elseif ($s->type==='image')
              @if($s->value)<img src="{{ \App\Support\Img::url($s->value) }}" class="h-20 rounded mb-2 object-cover">@endif
              <input type="file" name="settings_files[{{ $s->key }}]" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900">
            @else
              <input name="settings[{{ $s->key }}]" value="{{ old('settings.'.$s->key, $s->value) }}" class="{{ $inp }}">
            @endif
          </div>
        @endforeach
      </div>
    </div>
  @empty
    <p class="text-stone-500">No settings found. Run the database seeder to populate default content.</p>
  @endforelse

  <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Website Content</button>
</form>
@endsection
