<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $siswa = Siswa::where('email', strtolower(trim((string) $request->email)))->first();

        if ($siswa && Hash::check((string) $request->password, (string) $siswa->password)) {
            if (!$siswa->email_verified_at) {
                return back()->withErrors([
                    'login' => 'Email belum diverifikasi. Silakan verifikasi kode email terlebih dahulu.',
                ])->withInput($request->only('email'));
            }

            session()->forget('admin_logged_in');
            session([
                'siswa_logged_in' => true,
                'siswa_nis' => $siswa->nis,
                'siswa_nama' => $siswa->nama_lengkap,
                'siswa_kelas' => $siswa->kelas,
                'siswa_rombel' => $siswa->rombel_kelas,
            ]);

            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'login' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle siswa logout request
     */
    public function logout()
    {
        session()->forget(['siswa_logged_in', 'siswa_nis', 'siswa_nama', 'siswa_kelas', 'siswa_rombel']);
        session()->regenerate();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
