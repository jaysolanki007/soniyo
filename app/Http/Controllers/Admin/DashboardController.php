<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'appointments_today' => Appointment::whereDate('scheduled_at', $today)->count(),
            'revenue_month' => Invoice::whereMonth('created_at', $today->month)
                ->whereYear('created_at', $today->year)
                ->sum('total'),
            'customers' => Customer::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'staff' => Staff::where('is_active', true)->count(),
            'low_stock' => Product::whereColumn('stock_qty', '<=', 'low_stock_threshold')->count(),
        ];

        $upcoming = Appointment::with(['service', 'staff'])
            ->whereDate('scheduled_at', '>=', $today)
            ->whereNotIn('status', ['cancelled', 'no_show', 'completed'])
            ->orderBy('scheduled_at')
            ->limit(8)
            ->get();

        $recentCustomers = Customer::latest()->limit(5)->get();

        // Upcoming birthdays (next 30 days)
        $birthdays = Customer::whereNotNull('dob')->get()->filter(function ($c) {
            $next = Carbon::parse($c->dob)->setYear(now()->year);
            if ($next->isPast()) $next->addYear();
            return $next->diffInDays(now()) <= 30;
        })->take(5);

        $statusCounts = Appointment::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return view('admin.dashboard', compact('stats', 'upcoming', 'recentCustomers', 'birthdays', 'statusCounts'));
    }
}
