<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // Optional: block inactive branch users
            if ($user->user_role === 'Manager' && $user->branch && $user->branch->is_active == 0) {
                Auth::logout();
                return back()->withErrors(['branch' => 'Your branch is inactive. Contact admin.']);
            }

            // Redirect by role
            if ($user->user_role === 'Admin') {
                return redirect('/dashboard');
            }

            if ($user->user_role === 'Manager') {
                return redirect('/branch/dashboard');
            }

            // Default fallback
            Auth::logout();
            return back()->withErrors(['role' => 'Unauthorized role.']);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
