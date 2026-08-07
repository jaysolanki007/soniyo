@extends('admin.layout')
@section('title', $appointment->exists ? 'Edit Appointment' : 'New Appointment')

@section('content')
@php $a = $appointment; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $a->exists ? route('admin.appointments.update',$a) : route('admin.appointments.store') }}" class="max-w-3xl">
  @csrf
  @if ($a->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div>
      <label class="{{ $lbl }}">Existing Customer</label>
      <select name="customer_id" class="{{ $inp }}" onchange="if(this.value){const o=this.options[this.selectedIndex];document.querySelector('[name=customer_name]').value=o.dataset.name;document.querySelector('[name=customer_phone]').value=o.dataset.phone||'';}">
        <option value="">— New / walk-in —</option>
        @foreach ($customers as $c)
          <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-phone="{{ $c->phone }}" @selected(old('customer_id',$a->customer_id)==$c->id)>{{ $c->name }} ({{ $c->phone }})</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="{{ $lbl }}">Customer Name *</label>
      <input name="customer_name" value="{{ old('customer_name',$a->customer_name) }}" required class="{{ $inp }}">
    </div>
    <div>
      <label class="{{ $lbl }}">Phone</label>
      <input name="customer_phone" value="{{ old('customer_phone',$a->customer_phone) }}" class="{{ $inp }}">
    </div>
    <div>
      <label class="{{ $lbl }}">Email</label>
      <input name="customer_email" type="email" value="{{ old('customer_email',$a->customer_email) }}" class="{{ $inp }}">
    </div>
    <div>
      <label class="{{ $lbl }}">Service</label>
      <select name="service_id" class="{{ $inp }}">
        <option value="">—</option>
        @foreach ($services as $s)
          <option value="{{ $s->id }}" @selected(old('service_id',$a->service_id)==$s->id)>{{ $s->name }} (₹{{ number_format($s->price,0) }})</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="{{ $lbl }}">Stylist</label>
      <select name="staff_id" class="{{ $inp }}">
        <option value="">Any</option>
        @foreach ($staff as $s)
          <option value="{{ $s->id }}" @selected(old('staff_id',$a->staff_id)==$s->id)>{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="{{ $lbl }}">Date & Time *</label>
      <input name="scheduled_at" type="datetime-local" value="{{ old('scheduled_at',optional($a->scheduled_at)->format('Y-m-d\TH:i')) }}" required class="{{ $inp }}">
    </div>
    <div>
      <label class="{{ $lbl }}">Duration (min)</label>
      <input name="duration_min" type="number" value="{{ old('duration_min',$a->duration_min ?? 45) }}" class="{{ $inp }}">
    </div>
    <div>
      <label class="{{ $lbl }}">Price (₹)</label>
      <input name="price" type="number" step="0.01" value="{{ old('price',$a->price ?? 0) }}" class="{{ $inp }}">
    </div>
    <div>
      <label class="{{ $lbl }}">Status</label>
      <select name="status" class="{{ $inp }}">
        @foreach ($statuses as $k => $label)
          <option value="{{ $k }}" @selected(old('status',$a->status ?? 'pending')===$k)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="{{ $lbl }}">Source</label>
      <select name="source" class="{{ $inp }}">
        @foreach (['online'=>'Online','walk_in'=>'Walk-in','phone'=>'Phone','staff'=>'Staff'] as $k=>$label)
          <option value="{{ $k }}" @selected(old('source',$a->source ?? 'staff')===$k)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="sm:col-span-2">
      <label class="{{ $lbl }}">Notes</label>
      <textarea name="notes" rows="2" class="{{ $inp }}">{{ old('notes',$a->notes) }}</textarea>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Appointment</button>
    <a href="{{ route('admin.appointments.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
