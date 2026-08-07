@extends('admin.layout')
@section('title', 'Gallery')
@section('subtitle', 'Portfolio images shown on your website')

@section('actions')
<a href="{{ route('admin.gallery.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ Add Image</a>
@endsection

@section('content')
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
  @forelse ($items as $g)
    <div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden group relative">
      <div class="aspect-square bg-ink-600 overflow-hidden">
        <img src="{{ \App\Support\Img::url($g->image,'https://placehold.co/400/1c1813/D4AF37?text=Image') }}" class="w-full h-full object-cover" alt="{{ $g->title }}">
      </div>
      <div class="p-3">
        <div class="text-xs text-stone-300 truncate">{{ $g->title ?: 'Untitled' }}</div>
        <div class="text-[10px] text-stone-500 uppercase tracking-wider">{{ str_replace('_',' ',$g->category) }}</div>
        <div class="flex gap-3 mt-2 text-xs">
          <a href="{{ route('admin.gallery.edit', $g) }}" class="text-stone-400 hover:text-gold">Edit</a>
          <form action="{{ route('admin.gallery.destroy', $g) }}" method="POST" onsubmit="return confirm('Delete image?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
        </div>
      </div>
      @unless($g->is_public)<span class="absolute top-2 right-2 text-[9px] uppercase bg-black/60 text-stone-300 px-1.5 py-0.5 rounded">hidden</span>@endunless
    </div>
  @empty
    <p class="col-span-full text-center text-stone-500 py-10">No gallery images yet.</p>
  @endforelse
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
