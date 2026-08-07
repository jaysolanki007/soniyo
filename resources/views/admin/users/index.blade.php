@extends('admin.layout')
@section('title', 'Users & Access')
@section('subtitle', 'Create logins and control which modules each person can use')

@section('actions')
<a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">+ New User</a>
@endsection

@section('content')
<div class="rounded-xl border border-white/5 bg-ink-800 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="text-left text-[11px] uppercase tracking-wider text-stone-500 border-b border-white/5">
      <tr><th class="px-5 py-3">User</th><th class="px-5 py-3">Login</th><th class="px-5 py-3">Role</th><th class="px-5 py-3">Access</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Actions</th></tr>
    </thead>
    <tbody class="divide-y divide-white/5">
      @foreach ($users as $u)
        <tr class="hover:bg-white/5">
          <td class="px-5 py-3">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gold-soft to-gold-deep text-ink-900 font-semibold flex items-center justify-center">{{ strtoupper(substr($u->name,0,1)) }}</div>
              <div><div class="text-stone-100">{{ $u->name }}</div><div class="text-xs text-stone-500">{{ $u->phone }}</div></div>
            </div>
          </td>
          <td class="px-5 py-3 text-stone-400">{{ $u->email }}<div class="text-xs text-stone-600">{{ $u->username ? '@'.$u->username : '' }}</div></td>
          <td class="px-5 py-3"><span class="text-[10px] uppercase tracking-wider px-2 py-1 rounded bg-white/5 text-gold-soft">{{ str_replace('_',' ',$u->role) }}</span></td>
          <td class="px-5 py-3 text-stone-400">
            @if ($u->isSuperAdmin())<span class="text-gold-soft">All modules</span>
            @else {{ count($u->permissions ?? []) }} modules @endif
          </td>
          <td class="px-5 py-3">@if($u->is_active)<span class="text-emerald-400 text-xs">Active</span>@else<span class="text-stone-500 text-xs">Disabled</span>@endif</td>
          <td class="px-5 py-3 text-right whitespace-nowrap">
            <a href="{{ route('admin.users.edit', $u) }}" class="text-stone-400 hover:text-gold">Edit</a>
            @if ($u->id !== auth()->id())
              <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline ml-3">@csrf @method('PATCH')<button class="text-stone-400 hover:text-gold">{{ $u->is_active ? 'Disable' : 'Enable' }}</button></form>
              <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="text-rose-400 hover:text-rose-300">Delete</button></form>
            @else
              <span class="text-stone-600 ml-3 text-xs">(you)</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
