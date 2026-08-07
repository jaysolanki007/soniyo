<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Modules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Access is restricted to the super admin by the `module` middleware
    // (canAccess('users') is only true for super_admin).

    public function index()
    {
        $users = User::orderByRaw("FIELD(role,'super_admin','owner','manager','receptionist','staff')")->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(), 'modules' => Modules::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string'],
            'role' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'permissions' => $data['role'] === 'super_admin' ? null : array_values($data['permissions'] ?? []),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', ['user' => $user, 'modules' => Modules::all()]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string'],
            'role' => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
            'permissions' => ['array'],
            'permissions.*' => ['string'],
        ]);

        $user->name = $data['name'];
        $user->username = $data['username'] ?? null;
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->role = $data['role'];
        $user->permissions = $data['role'] === 'super_admin' ? null : array_values($data['permissions'] ?? []);
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function toggle(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot deactivate yourself.');
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('success', 'User '.($user->is_active ? 'activated' : 'deactivated').'.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot delete yourself.');
        abort_if($user->isSuperAdmin() && User::where('role', 'super_admin')->count() <= 1, 403, 'Cannot delete the last super admin.');
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
