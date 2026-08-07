<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'last_visit_at' => 'datetime',
        'is_active' => 'boolean',
        'total_spent' => 'decimal:2',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function preferredStylist()
    {
        return $this->belongsTo(Staff::class, 'preferred_stylist_id');
    }
}
