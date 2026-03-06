<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaAuthController extends Controller
{
    /**
     * Show the siswa login form
     */
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (session('siswa_logged_in')) {
            return redirect('/dashboard');
        }

        return view('siswa.login');
    }

    /**
     * Handle siswa login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'password' => 'required',
        ]);

        // Simple hardcoded credentials check (demo purpose)
        // Nanti bisa diganti dengan database check
        if ($request->nis === '12345' && $request->password === 'siswa') {
            session([
                'siswa_logged_in' => true,
                'siswa_nis' => $request->nis,
                'siswa_nama' => 'Siswa Demo'
            ]);
            return redirect('/dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'login' => 'NIS atau password salah.',
        ])->withInput($request->only('nis'));
    }

    /**
     * Handle siswa logout request
     */
    public function logout()
    {
        session()->forget(['siswa_logged_in', 'siswa_nis', 'siswa_nama']);

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
