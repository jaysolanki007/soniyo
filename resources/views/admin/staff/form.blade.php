@extends('admin.layout')
@section('title', $member->exists ? 'Edit Team Member' : 'New Team Member')

@section('content')
@php $m=$member; $inp='w-full px-4 py-2.5 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100'; $lbl='block text-[11px] uppercase tracking-[0.14em] text-gold/80 mb-1.5'; @endphp
<form method="POST" action="{{ $m->exists ? route('admin.staff.update',$m) : route('admin.staff.store') }}" enctype="multipart/form-data" class="max-w-3xl">
  @csrf
  @if ($m->exists) @method('PUT') @endif

  <div class="rounded-xl border border-white/5 bg-ink-800 p-6 grid sm:grid-cols-2 gap-5">
    <div><label class="{{ $lbl }}">Name *</label><input name="name" value="{{ old('name',$m->name) }}" required class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Title (e.g. Master Stylist)</label><input name="title" value="{{ old('title',$m->title) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Role</label>
      <select name="role" class="{{ $inp }}">
        @foreach (['stylist','beautician','makeup_artist','manager','receptionist','cashier'] as $r)
          <option value="{{ $r }}" @selected(old('role',$m->role)===$r)>{{ ucwords(str_replace('_',' ',$r)) }}</option>
        @endforeach
      </select>
    </div>
    <div><label class="{{ $lbl }}">Experience (years)</label><input name="experience_years" type="number" value="{{ old('experience_years',$m->experience_years) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Email</label><input name="email" type="email" value="{{ old('email',$m->email) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Phone</label><input name="phone" value="{{ old('phone',$m->phone) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Monthly Base Salary (₹)</label><input name="base_salary" type="number" step="0.01" value="{{ old('base_salary',$m->base_salary ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Commission Type</label>
      <select name="commission_type" class="{{ $inp }}">
        <option value="flat" @selected(old('commission_type',$m->commission_type ?? 'flat')==='flat')>Flat — one % on all sales</option>
        <option value="split" @selected(old('commission_type',$m->commission_type)==='split')>Split — separate service / product %</option>
      </select>
    </div>
    <div><label class="{{ $lbl }}">Service / Flat Commission (%)</label><input name="commission_percent" type="number" step="0.01" value="{{ old('commission_percent',$m->commission_percent ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Product Commission (%)</label><input name="product_commission_percent" type="number" step="0.01" value="{{ old('product_commission_percent',$m->product_commission_percent ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Monthly Sales Target (₹)</label><input name="target_amount" type="number" step="0.01" value="{{ old('target_amount',$m->target_amount ?? 0) }}" class="{{ $inp }}" placeholder="0 = no target"></div>
    <div><label class="{{ $lbl }}">Bonus When Target Met (₹)</label><input name="target_bonus" type="number" step="0.01" value="{{ old('target_bonus',$m->target_bonus ?? 0) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Skills</label><input name="skills" value="{{ old('skills',$m->skills) }}" placeholder="Balayage, Bridal…" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Photo URL</label><input name="photo_url" value="{{ old('photo_url', \Illuminate\Support\Str::startsWith($m->photo,'http') ? $m->photo : '') }}" placeholder="https://…" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">…or Upload Photo</label><input name="photo_file" type="file" accept="image/*" class="{{ $inp }} file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-gold file:text-ink-900"></div>
    <div><label class="{{ $lbl }}">Instagram URL</label><input name="social_instagram" value="{{ old('social_instagram',$m->social_instagram) }}" class="{{ $inp }}"></div>
    <div><label class="{{ $lbl }}">Sort Order</label><input name="sort_order" type="number" value="{{ old('sort_order',$m->sort_order ?? 0) }}" class="{{ $inp }}"></div>
    <div class="sm:col-span-2"><label class="{{ $lbl }}">Bio</label><textarea name="bio" rows="3" class="{{ $inp }}">{{ old('bio',$m->bio) }}</textarea></div>
    <div class="flex items-end gap-6">
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_public" value="1" @checked(old('is_public',$m->exists ? $m->is_public : true)) class="rounded bg-transparent border-white/20 text-gold"> Show on website</label>
      <label class="flex items-center gap-2 text-sm text-stone-300"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$m->exists ? $m->is_active : true)) class="rounded bg-transparent border-white/20 text-gold"> Active</label>
    </div>
  </div>

  <div class="flex gap-3 mt-5">
    <button class="px-6 py-2.5 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">Save Member</button>
    <a href="{{ route('admin.staff.index') }}" class="px-6 py-2.5 rounded-lg text-sm border border-white/15 text-stone-300 hover:border-gold">Cancel</a>
  </div>
</form>
@endsection
