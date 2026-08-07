@extends('admin.layout')
@section('title', $user->exists ? 'Edit User' : 'New User')
@section('subtitle', 'Set login details, role and module access')

@section('content')
@php $u=$user; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; $perms = old('permissions', $u->permissions ?? []); @endphp
<form method="POST" action="{{ $u->exists ? route('admin.users.update',$u) : route('admin.users.store') }}" class="max-w-4xl" x-data>
  @csrf
  @if ($u->exists) @method('PUT') @endif

  <div class="grid lg:grid-cols-2 gap-6">
    <!-- Account -->
    <div class="rounded-xl border border-white/5 bg-ink-800 p-6 space-y-5">
      <h2 class="serif text-xl text-stone-100">Account</h2>
      <div><label class="{{ $lbl }}">Full Name *</label><input name="name" value="{{ old('name',$u->name) }}" required class="{{ $inp }}"></div>
      <div class="grid sm:grid-cols-2 gap-4">
        <div><label class="{{ $lbl }}">Username</label><input name="username" value="{{ old('username',$u->username) }}" placeholder="e.g. priya" class="{{ $inp }}"></div>
        <div><label class="{{ $lbl }}">Phone</label><input name="phone" value="{{ old('phone',$u->phone) }}" class="{{ $inp }}"></div>
      </div>
      <div><label class="{{ $lbl }}">Email *</label><input name="email" type="email" value="{{ old('email',$u->email) }}" required class="{{ $inp }}"></div>
      <div><label class="{{ $lbl }}">Password {{ $u->exists ? '(leave blank to keep)' : '*' }}</label><input name="password" type="text" autocomplete="new-password" {{ $u->exists ? '' : 'required' }} class="{{ $inp }}" placeholder="Set a login password"></div>
      <div>
        <label class="{{ $lbl }}">Role</label>
        <select name="role" id="roleSelect" class="{{ $inp }}" onchange="document.getElementById('permBox').style.display = this.value==='super_admin' ? 'none':'block'">
          @foreach (['super_admin'=>'Super Admin (full access)','owner'=>'Owner','manager'=>'Branch Manager','receptionist'=>'Receptionist','staff'=>'Staff'] as $k=>$label)
            <option value="{{ $k }}" @selected(old('role',$u->role ?? 'receptionist')===$k)>{{ $label }}</option>
          @endforeach
        </select>
        <p class="text-xs text-stone-500 mt-2">Super Admin always has access to every module, including Users &amp; Access.</p>
      </div>
    </div>

    <!-- Module access -->
    <div class="rounded-xl border border-white/5 bg-ink-800 p-6" id="permBox" style="{{ old('role',$u->role)==='super_admin' ? 'display:none' : '' }}">
      <div class="flex items-center justify-between mb-4">
        <h2 class="serif text-xl text-stone-100">Module Access</h2>
        <label class="text-xs text-stone-400 flex items-center gap-2"><input type="checkbox" onclick="document.querySelectorAll('.permck').forEach(c=>c.checked=this.checked)" class="rounded bg-transparent border-white/20 text-gold"> Select all</label>
      </div>
      <p class="text-xs text-stone-500 mb-4">Tick the modules this user is allowed to open. Unticked modules stay hidden &amp; blocked. (Dashboard &amp; their own Profile are always available.)</p>
      <div class="grid sm:grid-cols-2 gap-2">
        @foreach ($modules as $key => $m)
          <label class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-white/10 hover:border-gold/40 cursor-pointer">
            <input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array($key,$perms)) class="permck rounded bg-transparent border-white/20 text-gold">
            <span class="w-5 text-center text-gold/80">{{ $m[1] }}</span>
            <span class="text-sm text-stone-200">{{ $m[0] }}</span>
          </label>
        @endforeach
      </div>
    </div>
  </div>

  <div class="flex gap-3 mt-6">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save User</button>
    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
