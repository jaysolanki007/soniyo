@extends('admin.layout')
@section('title', 'Coming Soon')

@section('content')
<div class="flex flex-col items-center justify-center text-center py-24">
  <div class="text-6xl mb-6">✨</div>
  <h2 class="serif text-4xl text-stone-100 mb-3">This module is on the way</h2>
  <p class="text-stone-400 max-w-md mb-8">This part of the Salon Management Suite is planned for an upcoming phase (POS, Inventory, Payroll, Marketing, Reports, Multi-branch, AI tools and more). The foundation, CRM, bookings and full Website CMS are live now.</p>
  <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-lg text-sm uppercase tracking-wider text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep">← Back to Dashboard</a>
</div>
@endsection
