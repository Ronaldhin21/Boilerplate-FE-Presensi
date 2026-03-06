<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLogin()
    {
        // If already logged in, redirect to admin dashboard
        if (session('admin_logged_in')) {
            return redirect('/admin/dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Simple hardcoded credentials check
        if ($request->username === 'admin' && $request->password === 'admin') {
            session(['admin_logged_in' => true]);
            return redirect('/admin/dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle admin logout request
     */
    public function logout()
    {
        session()->forget('admin_logged_in');

        return redirect('/admin/login')->with('success', 'Logout berhasil!');
    }
}
