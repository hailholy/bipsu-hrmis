<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        // Get stats
        $stats = [
            'present' => Attendance::where('date', $today)
                        ->where('status', 'present')
                        ->count(),
            'absent' => Attendance::where('date', $today)
                        ->where('status', 'absent')
                        ->count(),
            'late' => Attendance::where('date', $today)
                        ->where('status', 'late')
                        ->count(),
            'on_leave' => Attendance::where('date', $today)
                        ->where('status', 'on_leave')
                        ->count(),
        ];
        
        // Recent attendance records
        $recentAttendances = Attendance::with('user')
            ->where('date', $today)
            ->orderBy('check_in', 'desc')
            ->take(10)
            ->get();
            
        // Attendance trend data (last 7 days)
        $attendanceTrend = Attendance::selectRaw('date, 
            SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->whereBetween('date', [now()->subDays(7), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Department attendance
        $departmentAttendance = User::select('department')
            ->selectRaw('COUNT(users.id) as total,
                SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN attendances.status = "late" THEN 1 ELSE 0 END) as late')
            ->leftJoin('attendances', function($join) use ($today) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->where('attendances.date', '=', $today);
            })
            ->groupBy('department')
            ->get();

        return view('attendance', [
            'stats' => $stats,
            'recentAttendances' => $recentAttendances,
            'attendanceTrend' => $attendanceTrend,
            'departmentAttendance' => $departmentAttendance,
            'user' => auth()->user()
        ]);
    }
    
    public function checkIn(Request $request)
    {
        $user = auth()->user();
        $now = now();
        
        // Check if already checked in today
        $existing = Attendance::where('user_id', $user->id)
            ->where('date', $now->format('Y-m-d'))
            ->first();
            
        if ($existing) {
            return back()->with('error', 'You have already checked in today');
        }
        
        // Determine if late (after 8:30 AM)
        $status = 'present';
        if ($now->hour > 8 || ($now->hour == 8 && $now->minute > 30)) {
            $status = 'late';
        }
        
        Attendance::create([
            'user_id' => $user->id,
            'date' => $now->format('Y-m-d'),
            'check_in' => $now->format('H:i:s'),
            'status' => $status
        ]);
        
        return back()->with('success', 'Checked in successfully at ' . $now->format('h:i A'));
    }
    
    public function checkOut(Request $request)
    {
        $user = auth()->user();
        $now = now();
        
        // Find today's attendance record
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->format('Y-m-d'))
            ->first();
            
        if (!$attendance) {
            return back()->with('error', 'You need to check in first');
        }
        
        if ($attendance->check_out) {
            return back()->with('error', 'You have already checked out today');
        }
        
        $attendance->update([
            'check_out' => $now->format('H:i:s')
        ]);
        
        return back()->with('success', 'Checked out successfully at ' . $now->format('h:i A'));
    }
    
    public function stats()
    {
        $today = now()->format('Y-m-d');
        
        return [
            'present' => Attendance::where('date', $today)
                        ->where('status', 'present')
                        ->count(),
            'absent' => Attendance::where('date', $today)
                        ->where('status', 'absent')
                        ->count(),
            'late' => Attendance::where('date', $today)
                        ->where('status', 'late')
                        ->count(),
            'on_leave' => Attendance::where('date', $today)
                        ->where('status', 'on_leave')
                        ->count(),
        ];
    }

    public function qrCheckIn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);
        
        // Decode QR code data (format: user_id:timestamp:hash)
        $qrData = explode(':', $request->qr_code);
        
        if (count($qrData) !== 3) {
            return response()->json(['success' => false, 'message' => 'Invalid QR code']);
        }
        
        $userId = $qrData[0];
        $timestamp = $qrData[1];
        $hash = $qrData[2];
        
        // Verify hash
        $expectedHash = hash_hmac('sha256', $userId.':'.$timestamp, config('app.key'));
        
        if (!hash_equals($expectedHash, $hash)) {
            return response()->json(['success' => false, 'message' => 'Invalid QR code']);
        }
        
        // Check if QR code is expired (e.g., 30 seconds)
        if (time() - $timestamp > 30) {
            return response()->json(['success' => false, 'message' => 'QR code expired']);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        
        // Record attendance
        $now = now();
        $status = 'present';
        
        if ($now->hour > 8 || ($now->hour == 8 && $now->minute > 30)) {
            $status = 'late';
        }
        
        Attendance::create([
            'user_id' => $user->id,
            'date' => $now->format('Y-m-d'),
            'check_in' => $now->format('H:i:s'),
            'status' => $status
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully'
        ]);
    }
}