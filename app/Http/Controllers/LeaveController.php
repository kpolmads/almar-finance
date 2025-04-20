<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveCredit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeaveController extends Controller
{
    public function index()
    {
        // abort_unless(Gate::allows('hr_access') || Gate::allows('admin_access'), 403);
        
        $leaves = Leave::with(['employee', 'approvedBy'])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('pages.hr.leaves.index', compact('leaves'));
    }

    public function create()
    {
        // abort_unless(Gate::allows('hr_access') || Gate::allows('admin_access'), 403);
        
        $employees = User::whereHas('roles')->get();

        return view('pages.hr.leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:sick,vacation,maternity,paternity,service_incentive',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'days_requested' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,cancelled'
        ]);

        // Check if employee has sufficient credits before creating the leave
        $credit = LeaveCredit::where('employee_id', $validated['employee_id'])
            ->where('leave_type', $validated['leave_type'])
            ->first();

        if (!$credit) {
            return back()->with('error', 'No leave credits assigned for this type');
        }

        if ($credit->remaining_credits < $validated['days_requested']) {
            return back()->with('error', 'Insufficient leave credits available');
        }

        $leave = Leave::create($validated);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully');
    }

    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,cancelled',
            'approved_by' => 'required_if:status,approved|exists:users,id',
            'remarks' => 'nullable|string|max:255'
        ]);

        $currentStatus = $leave->status;
        $newStatus = $validated['status'];
        
        // Update leave status
        $leave->update($validated);

        // Handle credit updates based on status change
        $credit = LeaveCredit::where('employee_id', $leave->employee_id)
            ->where('leave_type', $leave->leave_type)
            ->first();

        if (!$credit) {
            return back()->with('error', 'No leave credits assigned for this type');
        }

        if ($newStatus === 'approved' && $currentStatus !== 'approved') {
            // Deduct credits when leave is approved
            $credit->decrement('remaining_credits', $leave->days_requested);
            $credit->increment('used_credits', $leave->days_requested);
        } elseif ($newStatus === 'cancelled' && $currentStatus === 'approved') {
            // Restore credits when approved leave is cancelled
            $credit->increment('remaining_credits', $leave->days_requested);
            $credit->decrement('used_credits', $leave->days_requested);
        }

        return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully');
    }

    public function approve(Request $request, Leave $leave)
    {
        abort_unless(Gate::allows('hr_access') || Gate::allows('admin_access') || Gate::allows('super_access'), 403);

        $validated = $request->validate([
            'remarks' => 'nullable|string|max:255'
        ]);

        // Check if employee has sufficient credits
        $credit = LeaveCredit::where('employee_id', $leave->employee_id)
            ->where('leave_type', $leave->leave_type)
            ->first();

        if (!$credit) {
            return back()->with('error', 'No leave credits assigned for this type');
        }

        if ($credit->remaining_credits < $leave->days_requested) {
            return back()->with('error', 'Insufficient leave credits available');
        }

        // Update leave and deduct credits
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'remarks' => $validated['remarks'] ?? null
        ]);

        // Deduct credits
        $credit->decrement('remaining_credits', $leave->days_requested);
        $credit->increment('used_credits', $leave->days_requested);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request approved successfully');
    }

    public function reject(Request $request, Leave $leave)
    {
        abort_unless(Gate::allows('hr_access') || Gate::allows('admin_access') || Gate::allows('super_access'), 403);

        $validated = $request->validate([
            'remarks' => 'required|string|max:255'
        ]);

        // If leave was previously approved, restore credits
        if ($leave->status === 'approved') {
            $credit = LeaveCredit::where('employee_id', $leave->employee_id)
                ->where('leave_type', $leave->leave_type)
                ->first();

            if ($credit) {
                $credit->increment('remaining_credits', $leave->days_requested);
                $credit->decrement('used_credits', $leave->days_requested);
            }
        }

        $leave->update([
            'status' => 'rejected',
            'remarks' => $validated['remarks']
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request rejected successfully');
    }

    public function employeeLeaves(User $employee)
    {
        abort_unless(Gate::allows('hr_access') || Gate::allows('admin_access') || Gate::allows('super_access'), 403);

        $leaves = Leave::where('employee_id', $employee->id)
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('pages.hr.leaves.employee', compact('employee', 'leaves'));
    }
}
