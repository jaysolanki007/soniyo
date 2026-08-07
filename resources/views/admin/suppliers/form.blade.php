@extends('admin.layout')
@section('title', $supplier->exists ? 'Edit Supplier' : 'New Supplier')

@section('content')
@php $s=$supplier; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $s->exists ? route('admin.suppliers.update',$s) : route('admin.suppliers.store') }}" class="max-w-3xl">
  @csrf
  @if ($s->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div><label class="{{ $lbl }}">Name *</label><input name="name" value="{{ old('name',$s->name) }}" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Company</label><input name="company" value="{{ old('company',$s->company) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Phone</label><input name="phone" value="{{ old('phone',$s->phone) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Email</label><input name="email" type="email" value="{{ old('email',$s->email) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Outstanding Balance (₹)</label><input name="outstanding_balance" type="number" step="0.01" value="{{ old('outstanding_balance',$s->outstanding_balance ?? 0) }}" class="{{ $inp }}"></div>
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Address</label><input name="address" value="{{ old('address',$s->address) }}" class="{{ $inp }}"></div>
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Notes</label><textarea name="notes" rows="3" class="{{ $inp }}">{{ old('notes',$s->notes) }}</textarea></div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Supplier</button>
    <a href="{{ route('admin.suppliers.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
