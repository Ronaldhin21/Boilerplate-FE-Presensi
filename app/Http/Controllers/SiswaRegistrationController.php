<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SiswaRegistrationController extends Controller
{
    public function showBiodataForm()
    {
        return view('siswa.register-biodata');
    }

    public function storeBiodata(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:siswas,nis',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'father_name' => 'required|string|max:150',
            'mother_name' => 'required|string|max:150',
            'place_of_birth' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'religion' => 'required|string|max:50',
            'kelas' => 'required|string|max:20',
            'rombel_kelas' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:30',
        ]);

        $request->session()->put('siswa_registration_biodata', $validated);

        return redirect()->route('siswa.register.account');
    }

    public function showAccountForm(Request $request)
    {
        if (!$request->session()->has('siswa_registration_biodata')) {
            return redirect()->route('siswa.register.biodata')
                ->with('error', 'Isi biodata terlebih dahulu.');
        }

        return view('siswa.register-account');
    }

    public function storeAccount(Request $request)
    {
        $biodata = $request->session()->get('siswa_registration_biodata');

        if (!$biodata) {
            return redirect()->route('siswa.register.biodata')
                ->with('error', 'Sesi registrasi habis. Isi biodata kembali.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:150|unique:siswas,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otpCode = (string) random_int(100000, 999999);

        $siswa = Siswa::create(array_merge($biodata, [
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'password_plain_encrypted' => Crypt::encryptString($validated['password']),
            'otp_code' => Hash::make($otpCode),
            'otp_expires_at' => now()->addMinutes(10),
            'email_verified_at' => null,
        ]));

        $request->session()->forget('siswa_registration_biodata');
        $request->session()->put('siswa_verify_email', $siswa->email);

        $emailSent = $this->sendOtpEmail($siswa->email, $otpCode);

        if ($this->shouldShowOtpPreview($emailSent)) {
            $request->session()->flash('otp_preview', $otpCode);
            $request->session()->flash('mail_warning', 'Email OTP belum dikirim ke inbox karena konfigurasi mail masih mode lokal. Gunakan kode OTP preview di bawah untuk lanjut.');
        }

        return redirect()->route('siswa.register.verify')
            ->with('success', 'Kode verifikasi telah dikirim ke email Anda.');
    }

    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email', $request->session()->get('siswa_verify_email'));

        if (!$email) {
            return redirect()->route('siswa.register.biodata')
                ->with('error', 'Silakan registrasi terlebih dahulu.');
        }

        return view('siswa.register-verify', ['email' => $email]);
    }

    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
        ]);

        $siswa = Siswa::where('email', strtolower(trim($validated['email'])))->first();

        if (!$siswa) {
            return back()->withErrors(['code' => 'Data akun tidak ditemukan.'])->withInput();
        }

        if ($siswa->email_verified_at) {
            return redirect()->route('login')->with('success', 'Email sudah terverifikasi, silakan login.');
        }

        if (!$siswa->otp_expires_at || now()->gt($siswa->otp_expires_at)) {
            return back()->withErrors(['code' => 'Kode verifikasi sudah kedaluwarsa. Silakan kirim ulang kode.'])->withInput();
        }

        if (!Hash::check($validated['code'], (string) $siswa->otp_code)) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.'])->withInput();
        }

        $siswa->update([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        $request->session()->forget('siswa_verify_email');

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Akun sudah aktif, silakan login.');
    }

    public function resendCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $siswa = Siswa::where('email', strtolower(trim($validated['email'])))->first();

        if (!$siswa) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        if ($siswa->email_verified_at) {
            return redirect()->route('login')->with('success', 'Email sudah terverifikasi, silakan login.');
        }

        $otpCode = (string) random_int(100000, 999999);

        $siswa->update([
            'otp_code' => Hash::make($otpCode),
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $emailSent = $this->sendOtpEmail($siswa->email, $otpCode);

        if ($this->shouldShowOtpPreview($emailSent)) {
            $request->session()->flash('otp_preview', $otpCode);
            $request->session()->flash('mail_warning', 'Email OTP belum dikirim ke inbox karena konfigurasi mail masih mode lokal. Gunakan kode OTP preview di bawah untuk lanjut.');
        }

        return back()->with('success', 'Kode verifikasi baru sudah dikirim.');
    }

    private function sendOtpEmail(string $email, string $otpCode): bool
    {
        try {
            Mail::raw("Kode verifikasi registrasi Presensi Anda: {$otpCode}. Kode berlaku 10 menit.", function ($message) use ($email) {
                $message->to($email)
                    ->subject('Kode Verifikasi Registrasi Presensi');
            });

            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim OTP email.', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function shouldShowOtpPreview(bool $emailSent): bool
    {
        $mailer = (string) config('mail.default');
        $usingLocalMailer = in_array($mailer, ['log', 'array'], true);

        return app()->environment('local') && ($usingLocalMailer || !$emailSent);
    }
}
