@extends('admin.layout')
@section('title', 'Team / Staff')
@section('subtitle', 'Stylists & team members (public profiles show on website)')

@section('actions')
<a href="{{ route('admin.staff.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New Member</a>
@endsection

@section('content')
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
  @forelse ($staff as $m)
    <div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden group">
      <div class="aspect-[4/5] bg-ink-600 overflow-hidden">
        <img src="{{ \App\Support\Img::url($m->photo, 'https://placehold.co/400x500/1c1813/D4AF37?text='.urlencode($m->name)) }}" class="w-full h-full object-cover" alt="{{ $m->name }}">
      </div>
      <div class="p-4">
        <div class="flex items-center justify-between">
          <h3 class="serif text-lg text-stone-100">{{ $m->name }}</h3>
          @if($m->is_public)<span class="text-[9px] uppercase tracking-wider text-emerald-400">Public</span>@else<span class="text-[9px] uppercase tracking-wider text-stone-500">Hidden</span>@endif
        </div>
        <p class="text-xs text-gold-soft">{{ $m->title }}</p>
        <div class="flex gap-3 mt-3 text-sm">
          <a href="{{ route('admin.staff.edit', $m) }}" class="text-stone-400 hover:text-gold">Edit</a>
          <form action="{{ route('admin.staff.destroy', $m) }}" method="POST" onsubmit="return confirm('Delete member?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
        </div>
      </div>
    </div>
  @empty
    <p class="col-span-full text-center text-stone-500 py-10">No team members yet.</p>
  @endforelse
</div>
<div class="mt-4">{{ $staff->links() }}</div>
@endsection
