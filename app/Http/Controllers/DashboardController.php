<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        // Get authenticated user
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login.show');
        }

        // Get today's date
        $today = now()->format('Y-m-d');

        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;

        // Get comprehensive payroll stats
        $payrollData = DB::table('payroll')
            ->select(
                DB::raw('SUM(net_salary) as total_payroll'),
                DB::raw('SUM(CASE WHEN payroll_status = "paid" THEN net_salary ELSE 0 END) as processed'),
                DB::raw('SUM(CASE WHEN payroll_status = "pending" THEN net_salary ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN users.role = "faculty" THEN payroll.net_salary ELSE 0 END) as faculty_payroll'),
                DB::raw('SUM(CASE WHEN users.role = "staff" THEN payroll.net_salary ELSE 0 END) as staff_payroll')
            )
            ->leftJoin('users', 'payroll.user_id', '=', 'users.id')
            ->whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->first();

        // Get last month's payroll for comparison
        $lastMonthPayroll = DB::table('payroll')
            ->whereMonth('payment_date', $lastMonth)
            ->whereYear('payment_date', $lastMonthYear)
            ->where('payroll_status', 'paid')
            ->sum('net_salary');

        // Calculate percentage change
        $payrollPercentageChange = $lastMonthPayroll > 0 
            ? round(($payrollData->processed - $lastMonthPayroll) / $lastMonthPayroll * 100)
            : 0;

        // Get notification count
        $unreadNotifications = $user->unreadNotifications()->count();

        // Get basic stats
        $stats = [
            'totalEmployees' => User::count(),
            'facultyCount' => User::where('role', 'employee')->count(),
        ];

        // Get attendance stats
    

        // Get recent check-ins
        $attendanceStats = DB::table('attendances')
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN status = "present" THEN user_id END) as present'),
                DB::raw('COUNT(DISTINCT CASE WHEN status = "absent" THEN user_id END) as absent'),
                DB::raw('COUNT(DISTINCT CASE WHEN status = "on_leave" THEN user_id END) as on_leave')
            )
            ->where('date', $today)
            ->first();

        // Get recent check-ins with proper user photo URL
        $recentCheckins = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select(
                'users.first_name',
                'users.last_name',
                'users.department',
                DB::raw('COALESCE(users.profile_photo_path, "default-profile.jpg") as profile_photo_path'),
                'attendances.check_in'
            )
            ->where('attendances.date', $today)
            ->whereNotNull('attendances.check_in')
            ->orderBy('attendances.check_in', 'desc')
            ->take(5)
            ->get();


        // Get upcoming payments
        $upcomingPayments = DB::table('payroll')
            ->join('users', 'payroll.user_id', '=', 'users.id')
            ->select(
                'users.first_name',
                'users.last_name',
                'users.department',
                'users.profile_photo_path',
                'payroll.net_salary',
                'payroll.payment_date'
            )
            ->where('payroll.payroll_status', 'pending')
            ->whereDate('payroll.payment_date', '>=', now())
            ->orderBy('payroll.payment_date')
            ->take(3)
            ->get();

        // Get department distribution
        $departmentDistribution = User::select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // Prepare color palette for chart
        $colorPalette = [
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 99, 132, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(199, 199, 199, 0.6)',
            'rgba(83, 102, 255, 0.6)'
        ];

        $borderPalette = [
            'rgba(54, 162, 235, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(199, 199, 199, 1)',
            'rgba(83, 102, 255, 1)'
        ];

        $deptLabels = [];
        $deptData = [];
        $colorMap = [];

        foreach ($departmentDistribution as $index => $dept) {
            $deptLabels[] = $dept->department;
            $deptData[] = $dept->count;
            $colorIndex = $index % count($colorPalette);
            $colorMap[$dept->department] = [
                'background' => $colorPalette[$colorIndex],
                'border' => $borderPalette[$colorIndex]
            ];
        }

        // Get unique departments for filter dropdown
        $departments = User::select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Get recent employees
        $recentEmployees = User::latest()
            ->take(5)
            ->get();

        $genderStats = [
            'male' => User::where('gender', 'Male')->count(),
            'female' => User::where('gender', 'Female')->count(),
            'other' => User::where('gender', 'Other')->count()
        ];

        return view('admin.dashboard', [
            'user' => $user,
            'unreadNotifications' => $unreadNotifications,
            'stats' => $stats,
            'payrollData' => $payrollData,
            'payrollPercentageChange' => $payrollPercentageChange,
            'attendanceStats' => $attendanceStats,
            'recentCheckins' => $recentCheckins,
            'upcomingPayments' => $upcomingPayments,
            'departmentDistribution' => $departmentDistribution,
            'departmentLabels' => $deptLabels,
            'departmentData' => $deptData,
            'departmentColorMap' => $colorMap,
            'departments' => $departments,
            'recentEmployees' => $recentEmployees,
            'genderStats' => $genderStats,
            'totalEmployees' => $stats['totalEmployees']
        ]);
    }
}