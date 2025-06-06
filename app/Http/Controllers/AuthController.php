<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
        {
            $credentials = $request->validate([
                'loginEmail' => 'required|email',
                'loginPassword' => 'required',
            ]);

            if (Auth::attempt([
                'email' => $credentials['loginEmail'],
                'password' => $credentials['loginPassword']
            ])) {
                $request->session()->regenerate();

                // Store user data in session
                $request->session()->put('user', Auth::user());
                
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.'
            ], 401);
        }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}