<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\Modules;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'phone', 'avatar', 'permissions', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'owner', 'manager', 'receptionist', 'staff']);
    }

    /**
     * Can this user access the given module key?
     * Super admin can access everything. Everyone else is limited to
     * their granted permissions (dashboard & profile always allowed).
     */
    public function canAccess(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (in_array($module, Modules::alwaysAllowed())) {
            return true;
        }

        if ($module === 'users') {
            return false; // user management is super-admin only
        }

        return in_array($module, $this->permissions ?? []);
    }
}
