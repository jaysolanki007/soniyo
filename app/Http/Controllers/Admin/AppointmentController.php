<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $appointments = Appointment::with(['service', 'staff', 'customer'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('scheduled_at')
            ->paginate(15)->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'status' => $status,
            'statuses' => Appointment::STATUSES,
        ]);
    }

    public function create()
    {
        return view('admin.appointments.form', $this->formData(new Appointment()));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['reference'] = 'APT-'.strtoupper(Str::random(6));
        Appointment::create($data);
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created.');
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.form', $this->formData($appointment));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($this->validated($request));
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }

    private function formData(Appointment $appointment): array
    {
        return [
            'appointment' => $appointment,
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'staff' => Staff::where('is_active', true)->orderBy('name')->get(),
            'customers' => Customer::orderBy('name')->get(),
            'statuses' => Appointment::STATUSES,
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string'],
            'customer_email' => ['nullable', 'email'],
            'service_id' => ['nullable', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:staff,id'],
            'scheduled_at' => ['required', 'date'],
            'duration_min' => ['nullable', 'integer'],
            'price' => ['nullable', 'numeric'],
            'status' => ['required', 'string'],
            'source' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
