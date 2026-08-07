<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $guarded = [];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'service_revenue' => 'decimal:2',
        'product_revenue' => 'decimal:2',
        'base_salary' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'target_bonus' => 'decimal:2',
        'incentive' => 'decimal:2',
        'deduction' => 'decimal:2',
        'gross' => 'decimal:2',
        'net' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
