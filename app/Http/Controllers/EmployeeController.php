<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::orderBy('created_at', 'desc')->get();
        $departments = User::select('department')->distinct()->orderBy('department')->pluck('department');

        // Make sure the profile_photo_url attribute is appended
        $employees->each(function ($employee) {
            $employee->append('profile_photo_url');
        });
        
        if (request()->wantsJson()) {
            return response()->json([
                'employees' => $employees,
                'departments' => $departments
            ]);
        }
        
        return view('employees', [
            'employees' => $employees,
            'departments' => $departments
        ]);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'required|string|max:50|unique:users',
            'department' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'password' => 'nullable|string|min:8',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Handle profile photo upload
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // Create employee
        $employee = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'employee_id' => $validated['employee_id'],
            'department' => $validated['department'],
            'role' => $validated['role'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'address' => $validated['address'],
            'hire_date' => $validated['hire_date'],
            'password' => Hash::make($validated['password'] ?? 'password'), // Default password if not provided
            'profile_photo_path' => $profilePhotoPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
            'employee' => $employee
        ]);
    }


    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$employee->id,
            'phone' => 'sometimes|string|max:20',
            'employee_id' => 'sometimes|string|max:50|unique:users,employee_id,'.$employee->id,
            'department' => 'sometimes|string|max:255',
            'role' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string|in:Male,Female,Other',
            'dob' => 'sometimes|date',
            'address' => 'sometimes|string',
            'hire_date' => 'sometimes|date',
            'user_status' => 'sometimes|string|in:Active,On Leave,Suspended,Terminated',
            'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($employee->profile_photo_path) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $profilePhotoPath;
        } elseif ($request->has('remove_profile_photo')) {
            // Handle explicit removal of profile photo
            if ($employee->profile_photo_path) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }
            $validated['profile_photo_path'] = null;
        }

        $employee->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
            'employee' => $employee->fresh()->append('profile_photo_url')
        ]);
    }
    

     // Delete employee
    public function destroy(User $employee)
    {
        // Delete profile photo if exists
        if ($employee->profile_photo_path) {
            Storage::disk('public')->delete($employee->profile_photo_path);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
}
