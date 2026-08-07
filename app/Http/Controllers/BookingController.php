<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email'],
            'service_id' => ['nullable', 'exists:services,id'],
            'scheduled_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        // Link or create a customer record (CRM)
        $customer = null;
        if (! empty($data['customer_email']) || ! empty($data['customer_phone'])) {
            $customer = Customer::firstOrCreate(
                array_filter([
                    'email' => $data['customer_email'] ?? null,
                ]) ?: ['phone' => $data['customer_phone']],
                [
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone'] ?? null,
                ]
            );
        }

        Appointment::create([
            'reference' => 'APT-'.strtoupper(Str::random(6)),
            'customer_id' => $customer?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_email' => $data['customer_email'] ?? null,
            'service_id' => $data['service_id'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? now()->addDay(),
            'status' => 'pending',
            'source' => 'online',
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('booking_success', 'Thank you! Your request has been received — our concierge will confirm within 24 hours.');
    }
}
