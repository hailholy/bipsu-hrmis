<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User; // Add this if you want to display user information

class LeaveController extends Controller
{
    public function index()
    {

         $employees = [];
    if (auth()->user()->role === 'admin') {
        $employees = User::where('user_status', 'Active')->get(['id', 'first_name', 'last_name']);
    }

        // Get counts for each leave status
        $pendingCount = Leave::where('status', 'pending')->count();
        $approvedCount = Leave::where('status', 'approved')->count();
        $rejectedCount = Leave::where('status', 'rejected')->count();
        
        // Get distinct leave types count
        $leaveTypesCount = Leave::distinct('type')->count('type');

        // Get recent leave requests (for the Recent Leave Requests section)
        $recentLeaves = Leave::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending approvals (for the Pending Approvals section)
        $pendingApprovals = Leave::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate yesterday's pending count
        $pendingYesterday = Leave::where('status', 'pending')
            ->whereDate('created_at', today()->subDay())
            ->count();
        $pendingDiff = $pendingCount - $pendingYesterday;

        // Calculate this week's approved count
        $approvedThisWeek = Leave::where('status', 'approved')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $lastWeekApproved = Leave::where('status', 'approved')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        $approvedDiff = $approvedThisWeek - $lastWeekApproved;

        // Calculate last week's rejected count
        $rejectedLastWeek = Leave::where('status', 'rejected')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        $rejectedDiff = $rejectedCount - $rejectedLastWeek;

        // Get leave statistics for the last 6 months
        $months = [];
        $approvedData = [];
        $pendingData = [];
        $rejectedData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('F');
            
            $approvedData[] = Leave::where('status', 'approved')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $pendingData[] = Leave::where('status', 'pending')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $rejectedData[] = Leave::where('status', 'rejected')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        
         // Get pending approvals (for the Pending Approvals section)
        $pendingApprovals = Leave::with(['user' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'department', 'profile_photo_path');
            }])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get leave types data (for the Leave Types section)
        $leaveTypes = [
            'Annual Leave' => [
                'icon' => 'fas fa-sun',
                'icon_color' => 'text-yellow-500',
                'total_days' => 30,
                'used_days' => Leave::where('type', 'Annual Leave')
                    ->where('status', 'approved')
                    ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1')),
                'period' => 'year'
            ],
            'Sick Leave' => [
                'icon' => 'fas fa-procedures',
                'icon_color' => 'text-green-500',
                'total_days' => null, // Unlimited
                'used_days' => Leave::where('type', 'Sick Leave')
                    ->where('status', 'approved')
                    ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1')),
                'period' => 'year'
            ],
            'Maternity Leave' => [
                'icon' => 'fas fa-baby',
                'icon_color' => 'text-purple-500',
                'total_days' => 90,
                'used_days' => Leave::where('type', 'Maternity Leave')
                    ->where('status', 'approved')
                    ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1')),
                'period' => 'case'
            ],
            'Conference Leave' => [
                'icon' => 'fas fa-chalkboard-teacher',
                'icon_color' => 'text-yellow-600',
                'total_days' => 10,
                'used_days' => Leave::where('type', 'Conference Leave')
                    ->where('status', 'approved')
                    ->sum(\DB::raw('DATEDIFF(end_date, start_date) + 1')),
                'period' => 'year'
            ]
        ];


        return view('admin.leave', [
            
            'employees' => $employees,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'leaveTypesCount' => $leaveTypesCount,
            'recentLeaves' => $recentLeaves,
            'pendingApprovals' => $pendingApprovals,
            'pendingDiff' => $pendingDiff,
            'approvedDiff' => $approvedDiff,
            'rejectedDiff' => $rejectedDiff,
            'chartMonths' => $months,
            'approvedData' => $approvedData,
            'pendingData' => $pendingData,
            'rejectedData' => $rejectedData,
            'leaveTypes' => $leaveTypes,
        ]);
    }

    public function chartData(Request $request)
    {
        $period = $request->query('period', 'monthly');
        $labels = [];
        $approved = [];
        $pending = [];
        $rejected = [];

        if ($period === 'quarterly') {
            // Quarterly data
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subQuarters($i)->startOfQuarter();
                $end = now()->subQuarters($i)->endOfQuarter();
                $labels[] = 'Q' . $start->quarter . ' ' . $start->year;
                
                $approved[] = Leave::where('status', 'approved')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
                    
                $pending[] = Leave::where('status', 'pending')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
                    
                $rejected[] = Leave::where('status', 'rejected')
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }
        } elseif ($period === 'yearly') {
            // Yearly data (last 4 years)
            for ($i = 3; $i >= 0; $i--) {
                $year = now()->subYears($i)->year;
                $labels[] = $year;
                
                $approved[] = Leave::where('status', 'approved')
                    ->whereYear('created_at', $year)
                    ->count();
                    
                $pending[] = Leave::where('status', 'pending')
                    ->whereYear('created_at', $year)
                    ->count();
                    
                $rejected[] = Leave::where('status', 'rejected')
                    ->whereYear('created_at', $year)
                    ->count();
            }
        } else {
            // Default monthly data (last 6 months)
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->format('F');
                
                $approved[] = Leave::where('status', 'approved')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                    
                $pending[] = Leave::where('status', 'pending')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                    
                $rejected[] = Leave::where('status', 'rejected')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        }

        return response()->json([
            'labels' => $labels,
            'approved' => $approved,
            'pending' => $pending,
            'rejected' => $rejected
        ]);
    }

    public function update(Request $request, Leave $leave)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Update the leave status
        $leave->update([
            'status' => $request->status,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Redirect back with success message
        return back()->with('success', 'Leave request has been ' . $request->status);
    }

    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'type' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string',
    ]);

    // For non-admin users, ensure they can only create requests for themselves
    if (auth()->user()->role !== 'admin' && $request->user_id != auth()->id()) {
        abort(403);
    }

    $leave = Leave::create([
        'user_id' => $request->user_id,
        'type' => $request->type,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
        'status' => auth()->user()->role === 'admin' ? 'approved' : 'pending',
    ]);

    // Optionally add a notification here

    return back()->with('success', 'Leave request created successfully');
}
}