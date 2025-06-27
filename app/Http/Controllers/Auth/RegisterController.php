<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function store(Request $request)
        {
            $request->validate([
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'registerEmail' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'registerPassword' => ['required', 'confirmed', Rules\Password::defaults()],
                'employeeId' => ['required', 'string', 'max:255', 'unique:users,employee_id'],
                'department' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'in:employee,hr,admin'],
            ]);

            $user = User::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->registerEmail,
                'password' => Hash::make($request->registerPassword),
                'employee_id' => $request->employeeId,
                'department' => $request->department,
                'role' => $request->role,
            ]);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard')
            ]);
        }
}