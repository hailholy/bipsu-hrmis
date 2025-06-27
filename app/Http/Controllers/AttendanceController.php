<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->format('Y-m-d');
        $user = auth()->user();
        
        // Get user-specific data
        $todayRecord = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        $history = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();
            
        $monthlySummary = $this->getMonthlySummary($user->id);

        // Get attendance statistics
        $todayStats = $this->getAttendanceStats($today);
        $yesterdayStats = $this->getAttendanceStats(Carbon::yesterday()->format('Y-m-d'));

        // Calculate percentage changes
        $percentageChanges = [
            'present' => $this->calculatePercentageChange($todayStats['present'], $yesterdayStats['present']),
            'late' => $this->calculatePercentageChange($todayStats['late'], $yesterdayStats['late']),
            'absent' => $this->calculatePercentageChange($todayStats['absent'], $yesterdayStats['absent']),
            'on_leave' => $this->calculatePercentageChange($todayStats['on_leave'], $yesterdayStats['on_leave']),
        ];

        // Filter attendance records
        $attendanceQuery = Attendance::with('user')
            ->when($request->date, function($query) use ($request) {
                $query->where('date', $request->date);
            })
            ->when($request->department, function($query) use ($request) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('department', $request->department);
                });
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            });

        // Handle sorting
        $sort = $request->sort;
        $direction = $request->direction ?? 'asc';
        
        if ($sort) {
            switch ($sort) {
                case 'employee':
                    $attendanceQuery->join('users', 'attendances.user_id', '=', 'users.id')
                        ->orderBy('users.full_name', $direction)
                        ->select('attendances.*');
                    break;
                case 'department':
                    $attendanceQuery->join('users', 'attendances.user_id', '=', 'users.id')
                        ->orderBy('users.department', $direction)
                        ->select('attendances.*');
                    break;
                case 'check_in':
                    $attendanceQuery->orderBy('check_in', $direction);
                    break;
                case 'check_out':
                    $attendanceQuery->orderBy('check_out', $direction);
                    break;
                case 'status':
                    $attendanceQuery->orderBy('status', $direction);
                    break;
                default:
                    $attendanceQuery->orderBy('date', 'desc');
            }
        } else {
            $attendanceQuery->orderBy('date', 'desc');
        }

        $attendanceRecords = $attendanceQuery->paginate(10);

        return view('admin.attendance', [
            'todayRecord' => $todayRecord,
            'history' => $history,
            'monthlySummary' => $monthlySummary,
            'todayStats' => $todayStats,
            'percentageChanges' => $percentageChanges,
            'attendanceRecords' => $attendanceRecords,
            'departments' => User::whereNotNull('department')
                ->distinct()
                ->pluck('department'),
            'user' => $user
        ]);
    }
    
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $now = now();
        $today = $now->format('Y-m-d');
        
        if (Attendance::where('user_id', $user->id)->where('date', $today)->exists()) {
            return back()->with('error', 'You have already checked in today');
        }
        
        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => $now->format('H:i:s'),
            'status' => ($now->hour > 8 || ($now->hour == 8 && $now->minute > 30)) ? 'Late' : 'Present',
            'method' => $request->method ?? 'Manual'
        ]);
        
        return back()->with('success', 'Checked in successfully at ' . $now->format('h:i A'));
    }
    
    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->firstOrFail();
            
        if ($attendance->check_out) {
            return back()->with('error', 'You have already checked out today');
        }
        
        $attendance->update([
            'check_out' => now()->format('H:i:s'),
            'method' => $request->method ?? 'Manual'
        ]);
        
        return back()->with('success', 'Checked out successfully at ' . now()->format('h:i A'));
    }
    

    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();
            return response()->json([
                'success' => true,
                'message' => 'Attendance record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attendance record'
            ], 500);
        }
    }
    
    private function getMonthlySummary($userId)
    {
        return Attendance::where('user_id', $userId)
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late_days,
                SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent_days
            ')
            ->first();
    }

    private function getAttendanceStats($date)
    {
        $stats = Attendance::where('date', $date)
            ->selectRaw('
                SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = "On_Leave" THEN 1 ELSE 0 END) as on_leave
            ')
            ->first();

        return [
            'present' => $stats->present ?? 0,
            'late' => $stats->late ?? 0,
            'absent' => $stats->absent ?? 0,
            'on_leave' => $stats->on_leave ?? 0
        ];
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current == 0 ? 0 : 100;
        }
        return round((($current - $previous) / $previous) * 100);
    }

    public function getStatsByPeriod(Request $request)
    {
        $period = $request->input('period');
        $date = null;

        switch ($period) {
            case 'yesterday':
                $date = Carbon::yesterday()->format('Y-m-d');
                break;
            case 'week':
                $date = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                break;
            case 'month':
                $date = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
                break;
            default: // today
                $date = Carbon::today()->format('Y-m-d');
        }

        if (is_array($date)) {
            $stats = Attendance::whereBetween('date', $date)
                ->selectRaw('
                    SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                    SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = "On_Leave" THEN 1 ELSE 0 END) as on_leave
                ')
                ->first();
        } else {
            $stats = Attendance::where('date', $date)
                ->selectRaw('
                    SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                    SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = "On_Leave" THEN 1 ELSE 0 END) as on_leave
                ')
                ->first();
        }

        return response()->json([
            'success' => true,
            'stats' => [
                'present' => $stats->present ?? 0,
                'late' => $stats->late ?? 0,
                'absent' => $stats->absent ?? 0,
                'on_leave' => $stats->on_leave ?? 0
            ]
        ]);
    }

    public function export(Request $request)
    {
        $attendanceQuery = Attendance::with('user')
            ->when($request->date, function($query) use ($request) {
                $query->where('date', $request->date);
            })
            ->when($request->department, function($query) use ($request) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('department', $request->department);
                });
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('date', 'desc');

        $attendances = $attendanceQuery->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=attendance_records_' . now()->format('Y-m-d') . '.csv',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Employee Name',
                'Employee ID',
                'Department',
                'Date',
                'Check In',
                'Check Out',
                'Status',
                'Method'
            ]);

            // Add data rows
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->user->full_name,
                    $attendance->user->employee_id,
                    $attendance->user->department,
                    $attendance->date,
                    $attendance->check_in ? Carbon::parse($attendance->check_in)->format('h:i A') : '-',
                    $attendance->check_out ? Carbon::parse($attendance->check_out)->format('h:i A') : '-',
                    $attendance->status,
                    $attendance->method
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getMonthlyComparisonData(Request $request)
    {
        $months = $request->input('months', 12); // Default to 12 months if not specified
        $endDate = now()->endOfMonth();
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        // Get all months in the range
        $monthsRange = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $monthsRange[] = $currentDate->format('Y-m');
            $currentDate->addMonth();
        }

        // Get attendance data for each month
        $monthlyData = [];
        foreach ($monthsRange as $month) {
            $start = Carbon::parse($month)->startOfMonth();
            $end = Carbon::parse($month)->endOfMonth();

            $stats = Attendance::whereBetween('date', [$start, $end])
                ->selectRaw('
                    SUM(CASE WHEN status = "Present" THEN 1 ELSE 0 END) as present,
                    SUM(CASE WHEN status = "Late" THEN 1 ELSE 0 END) as late,
                    SUM(CASE WHEN status = "Absent" THEN 1 ELSE 0 END) as absent,
                    SUM(CASE WHEN status = "On_Leave" THEN 1 ELSE 0 END) as on_leave
                ')
                ->first();

            $monthlyData[] = [
                'month' => $start->format('M Y'),
                'present' => $stats->present ?? 0,
                'late' => $stats->late ?? 0,
                'absent' => $stats->absent ?? 0,
                'on_leave' => $stats->on_leave ?? 0
            ];
        }

        // Prepare data for chart
        $labels = array_column($monthlyData, 'month');
        $present = array_column($monthlyData, 'present');
        $late = array_column($monthlyData, 'late');
        $absent = array_column($monthlyData, 'absent');
        $on_leave = array_column($monthlyData, 'on_leave');

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'current' => [
                    'present' => $present,
                    'late' => $late,
                    'absent' => $absent,
                    'on_leave' => $on_leave
                ]
            ]
        ]);
    }
    
}