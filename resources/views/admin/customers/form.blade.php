@extends('admin.layout')
@section('title', $customer->exists ? 'Edit Customer' : 'New Customer')

@section('content')
@php $a = $customer; @endphp
<form method="POST" action="{{ $a->exists ? route('admin.customers.update',$a) : route('admin.customers.store') }}" class="max-w-3xl">
  @csrf
  @if ($a->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Full Name *</label>
      <input name="name" value="{{ old('name',$a->name) }}" required class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Gender</label>
      <select name="gender" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
        @foreach (['','female','male','other'] as $g)
          <option value="{{ $g }}" @selected(old('gender',$a->gender)===$g)>{{ $g ? ucfirst($g) : '—' }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Phone</label>
      <input name="phone" value="{{ old('phone',$a->phone) }}" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Email</label>
      <input name="email" type="email" value="{{ old('email',$a->email) }}" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Date of Birth</label>
      <input name="dob" type="date" value="{{ old('dob',optional($a->dob)->format('Y-m-d')) }}" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Membership</label>
      <select name="membership" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
        @foreach (['none','silver','gold','platinum'] as $m)
          <option value="{{ $m }}" @selected(old('membership',$a->membership)===$m)>{{ ucfirst($m) }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Preferred Stylist</label>
      <select name="preferred_stylist_id" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
        <option value="">—</option>
        @foreach ($stylists as $s)
          <option value="{{ $s->id }}" @selected(old('preferred_stylist_id',$a->preferred_stylist_id)==$s->id)>{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Loyalty Points</label>
      <input name="loyalty_points" type="number" value="{{ old('loyalty_points',$a->loyalty_points ?? 0) }}" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div class="sm:col-span-2">
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Address</label>
      <input name="address" value="{{ old('address',$a->address) }}" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div class="sm:col-span-2">
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Allergies / Skin concerns</label>
      <input name="allergies" value="{{ old('allergies',$a->allergies) }}" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">
    </div>
    <div class="sm:col-span-2">
      <label class="block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5">Notes</label>
      <textarea name="notes" rows="3" class="w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100">{{ old('notes',$a->notes) }}</textarea>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Customer</button>
    <a href="{{ route('admin.customers.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
