@extends('admin.layout')
@section('title', 'Reviews')
@section('subtitle', 'Client testimonials shown on your website')

@section('actions')
<a href="{{ route('admin.testimonials.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Review</a>
@endsection

@section('content')
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
  @forelse ($testimonials as $t)
    <div class="rounded-xl border border-white/5 bg-ink-800 p-5">
      <div class="text-gold tracking-widest mb-2">{{ str_repeat('★',$t->rating) }}<span class="text-stone-700">{{ str_repeat('★',5-$t->rating) }}</span></div>
      <p class="serif italic text-stone-200 mb-4">"{{ \Illuminate\Support\Str::limit($t->quote,140) }}"</p>
      <div class="flex items-center justify-between">
        <div><div class="text-stone-100 text-sm">{{ $t->customer_name }}</div><div class="text-xs text-stone-500">{{ $t->role }}</div></div>
        <div class="flex gap-3 text-xs">
          <a href="{{ route('admin.testimonials.edit', $t) }}" class="text-stone-400 hover:text-gold">Edit</a>
          <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete review?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
        </div>
      </div>
      @unless($t->is_public)<span class="inline-block mt-2 text-[9px] uppercase tracking-wider text-stone-500">hidden</span>@endunless
    </div>
  @empty
    <p class="col-span-full text-center text-stone-500 py-10">No reviews yet.</p>
  @endforelse
</div>
<div class="mt-4">{{ $testimonials->links() }}</div>
@endsection
