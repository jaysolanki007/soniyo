<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payslip;
use App\Models\Staff;
use App\Support\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $period)->startOfMonth();

        $payslips = Payslip::with('staff')->where('period', $period)
            ->get()
            ->sortBy(fn ($p) => $p->staff->name ?? '');

        $totals = [
            'gross' => $payslips->sum('gross'),
            'net' => $payslips->sum('net'),
            'commission' => $payslips->sum('commission_amount'),
            'paid' => $payslips->where('status', 'paid')->sum('net'),
        ];

        return view('admin.payroll.index', [
            'payslips' => $payslips,
            'totals' => $totals,
            'period' => $period,
            'label' => $month->format('F Y'),
            'staffCount' => Staff::where('is_active', true)->count(),
        ]);
    }

    public function generate(Request $request)
    {
        $period = $request->validate(['period' => ['required', 'string']])['period'];
        $month = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $created = 0;
        foreach (Staff::where('is_active', true)->get() as $staff) {
            $existing = Payslip::where('staff_id', $staff->id)->where('period', $period)->first();
            if ($existing && $existing->status === 'paid') {
                continue; // never overwrite a paid payslip
            }

            $c = Commission::forStaff($staff, $start, $end);
            $incentive = (float) ($existing->incentive ?? 0);
            $deduction = (float) ($existing->deduction ?? 0);
            $base = (float) $staff->base_salary;
            $gross = $base + $c['commission'] + $c['target_bonus'] + $incentive;
            $net = $gross - $deduction;

            Payslip::updateOrCreate(
                ['staff_id' => $staff->id, 'period' => $period],
                [
                    'period_label' => $month->format('F Y'),
                    'period_start' => $start->toDateString(),
                    'period_end' => $end->toDateString(),
                    'service_revenue' => $c['service_revenue'],
                    'product_revenue' => $c['product_revenue'],
                    'base_salary' => $base,
                    'commission_amount' => $c['commission'],
                    'target_bonus' => $c['target_bonus'],
                    'incentive' => $incentive,
                    'deduction' => $deduction,
                    'gross' => $gross,
                    'net' => $net,
                    'status' => 'draft',
                ]
            );
            $created++;
        }

        return redirect()->route('admin.payroll.index', ['period' => $period])
            ->with('success', "Payroll generated for {$created} staff for {$month->format('F Y')}.");
    }

    public function edit(Payslip $payroll)
    {
        return view('admin.payroll.edit', ['slip' => $payroll->load('staff')]);
    }

    public function update(Request $request, Payslip $payroll)
    {
        $data = $request->validate([
            'incentive' => ['nullable', 'numeric', 'min:0'],
            'deduction' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $incentive = (float) ($data['incentive'] ?? 0);
        $deduction = (float) ($data['deduction'] ?? 0);
        $gross = (float) $payroll->base_salary + (float) $payroll->commission_amount + (float) $payroll->target_bonus + $incentive;

        $payroll->update([
            'incentive' => $incentive,
            'deduction' => $deduction,
            'notes' => $data['notes'] ?? null,
            'gross' => $gross,
            'net' => $gross - $deduction,
        ]);

        return redirect()->route('admin.payroll.index', ['period' => $payroll->period])->with('success', 'Payslip updated.');
    }

    public function markPaid(Payslip $payroll)
    {
        $payroll->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Payslip marked as paid.');
    }

    public function show(Payslip $payroll)
    {
        return view('admin.payroll.show', ['slip' => $payroll->load('staff')]);
    }

    public function destroy(Payslip $payroll)
    {
        $period = $payroll->period;
        $payroll->delete();
        return redirect()->route('admin.payroll.index', ['period' => $period])->with('success', 'Payslip deleted.');
    }
}
