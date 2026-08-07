<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $guarded = [];

    protected $casts = [
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'joining_date' => 'date',
        'commission_percent' => 'decimal:2',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
