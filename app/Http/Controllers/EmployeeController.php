<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::orderBy('created_at', 'desc')->get();
        $departments = User::select('department')->distinct()->orderBy('department')->pluck('department');
        
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'employee_id' => 'required|unique:users',
            'department' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make('default123'), // Default password
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'role' => $request->role,
            'category' => $request->category ?? 'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee added successfully',
            'employee' => $user
        ]);
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$employee->id,
            'employee_id' => 'required|unique:users,employee_id,'.$employee->id,
            'department' => 'required',
            'role' => 'required',
        ]);

        $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'role' => $request->role,
            'category' => $request->category ?? 'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully',
            'employee' => $employee
        ]);
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
}