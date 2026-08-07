@extends('admin.layout')
@section('title', 'My Profile')
@section('subtitle', 'Update your personal details and password')

@section('content')
@php $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<div class="grid lg:grid-cols-2 gap-6 max-w-4xl">

  <!-- Details -->
  <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="rounded-xl border border-white/5 bg-ink-800 p-6 space-y-5">
    @csrf @method('PUT')
    <div class="flex items-center gap-4">
      <div class="w-16 h-16 rounded-full overflow-hidden bg-gradient-to-br from-gold-soft to-gold-deep text-ink-900 text-2xl font-semibold flex items-center justify-center">
        @if ($user->avatar)<img src="{{ \App\Support\Img::url($user->avatar) }}" class="w-full h-full object-cover">@else{{ strtoupper(substr($user->name,0,1)) }}@endif
      </div>
      <div>
        <h2 class="serif text-xl text-stone-100">{{ $user->name }}</h2>
        <span class="text-[10px] uppercase tracking-wider text-gold-soft">{{ str_replace('_',' ',$user->role) }}</span>
      </div>
    </div>
    <div><label class="{{ $lbl }}">Full Name *</label><input name="name" value="{{ old('name',$user->name) }}" required class="{{ $inp }}"></div>
    <div class="grid sm:grid-cols-2 gap-4">
      <div><label class="{{ $lbl }}">Username</label><input name="username" value="{{ old('username',$user->username) }}" class="{{ $inp }}"></div>
      <div><label class="{{ $lbl }}">Phone</label><input name="phone" value="{{ old('phone',$user->phone) }}" class="{{ $inp }}"></div>
    </div>
    <div><label class="{{ $lbl }}">Email *</label><input name="email" type="email" value="{{ old('email',$user->email) }}" required class="{{ $inp }}"></div>
    <div class="grid sm:grid-cols-2 gap-4">
      <div><label class="{{ $lbl }}">Avatar URL</label><input name="avatar_url" value="{{ old('avatar_url', \Illuminate\Support\Str::startsWith($user->avatar,'http') ? $user->avatar : '') }}" class="{{ $inp }}"></div>
      <div><label class="{{ $lbl }}">…or Upload</label><input name="avatar_file" type="file" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900"></div>
    </div>
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Profile</button>
  </form>

  <!-- Password -->
  <form method="POST" action="{{ route('admin.profile.password') }}" class="rounded-xl border border-white/5 bg-ink-800 p-6 space-y-5 self-start">
    @csrf @method('PUT')
    <h2 class="serif text-xl text-stone-100">Change Password</h2>
    <div><label class="{{ $lbl }}">Current Password *</label><input name="current_password" type="password" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">New Password *</label><input name="password" type="password" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Confirm New Password *</label><input name="password_confirmation" type="password" required class="{{ $inp }}"></div>
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Update Password</button>
  </form>
</div>
@endsection
