<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $customers = Customer::when($q, fn ($query) =>
                $query->where('name', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%"))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.customers.index', compact('customers', 'q'));
    }

    public function create()
    {
        return view('admin.customers.form', ['customer' => new Customer(), 'stylists' => Staff::all()]);
    }

    public function store(Request $request)
    {
        Customer::create($this->validated($request));
        return redirect()->route('admin.customers.index')->with('success', 'Customer added.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['appointments.service', 'appointments.staff', 'preferredStylist']);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.form', ['customer' => $customer, 'stylists' => Staff::all()]);
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($this->validated($request));
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'preferred_stylist_id' => ['nullable', 'exists:staff,id'],
            'allergies' => ['nullable', 'string'],
            'membership' => ['nullable', 'in:none,silver,gold,platinum'],
            'loyalty_points' => ['nullable', 'integer'],
        ]);
    }
}
