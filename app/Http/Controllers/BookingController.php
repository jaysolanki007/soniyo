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
        try {
            $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email'],
            'service_id' => ['nullable', 'exists:services,id'],
                'scheduled_at' => ['nullable', 'date'],
                'notes' => ['nullable', 'string'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Homepage is edge-cached without sessions, so flashed errors
            // wouldn't render — signal failure via query string instead.
            return redirect('/?booking_error=1#book');
        }

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

        // The homepage is edge-cached without sessions, so a session flash
        // can't be displayed there — signal success via query string instead.
        return redirect('/?booked=1#book');
    }
}
