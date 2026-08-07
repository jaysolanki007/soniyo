<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Support\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $rows = Staff::where('is_active', true)->orderBy('name')->get()->map(function ($staff) use ($start, $end) {
            $c = Commission::forStaff($staff, $start, $end);
            $c['staff'] = $staff;
            return $c;
        });

        $totals = [
            'revenue' => $rows->sum('total_revenue'),
            'commission' => $rows->sum('commission'),
            'bonus' => $rows->sum('target_bonus'),
            'earning' => $rows->sum('total_earning'),
        ];

        return view('admin.commissions.index', [
            'rows' => $rows,
            'totals' => $totals,
            'period' => $period,
            'label' => $month->format('F Y'),
        ]);
    }
}
