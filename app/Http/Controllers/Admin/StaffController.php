<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::orderBy('sort_order')->paginate(15);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.form', ['member' => new Staff()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->resolveImage($request, 'photo_file', 'photo_url');
        $data['is_public'] = $request->boolean('is_public');
        $data['is_active'] = $request->boolean('is_active');
        Staff::create($data);
        return redirect()->route('admin.staff.index')->with('success', 'Team member added.');
    }

    public function edit(Staff $staff)
    {
        return view('admin.staff.form', ['member' => $staff]);
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->resolveImage($request, 'photo_file', 'photo_url', $staff->photo);
        $data['is_public'] = $request->boolean('is_public');
        $data['is_active'] = $request->boolean('is_active');
        $staff->update($data);
        return redirect()->route('admin.staff.index')->with('success', 'Team member updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return back()->with('success', 'Team member deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string'],
            'role' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer'],
            'commission_percent' => ['nullable', 'numeric'],
            'commission_type' => ['nullable', 'in:flat,split'],
            'product_commission_percent' => ['nullable', 'numeric'],
            'base_salary' => ['nullable', 'numeric'],
            'target_amount' => ['nullable', 'numeric'],
            'target_bonus' => ['nullable', 'numeric'],
            'social_instagram' => ['nullable', 'string'],
            'social_linkedin' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
