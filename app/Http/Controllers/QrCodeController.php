<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QrCodeController extends Controller
{
    /**
     * Generate QR Code untuk presensi
     */
    public function generate(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'durasi_hadir' => 'required|integer|min:1|max:120',
            'durasi_maksimal' => 'required|integer|min:1|max:180|gt:durasi_hadir',
        ]);

        // Parse waktu mulai
        $waktuMulai = Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
        $durasiHadir = (int) $request->durasi_hadir;
        $durasiMaksimal = (int) $request->durasi_maksimal;
        $batasHadir = $waktuMulai->copy()->addMinutes($durasiHadir);
        $batasTerlambat = $waktuMulai->copy()->addMinutes($durasiMaksimal);

        // Generate unique code
        $code = Str::random(32);

        // Simpan ke database
        $qrCode = QrCode::create([
            'code' => $code,
            'hari' => $request->hari,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $waktuMulai->format('H:i:s'),
            'batas_hadir' => $batasHadir->format('H:i:s'),
            'batas_terlambat' => $batasTerlambat->format('H:i:s'),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'QR Code berhasil dibuat!')
            ->with('qr_code', $qrCode);
    }

    /**
     * Show QR Code
     */
    public function show($id)
    {
        $qrCode = QrCode::findOrFail($id);

        return view('admin.qr-show', compact('qrCode'));
    }

    /**
     * Scan QR Code dan catat presensi
     */
    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Cari QR Code
        $qrCode = QrCode::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau sudah tidak aktif.',
            ], 404);
        }

        // Cek apakah siswa sudah presensi
        $siswaPresensi = Presensi::where('qr_code_id', $qrCode->id)
            ->where('siswa_nis', session('siswa_nis'))
            ->first();

        if ($siswaPresensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi.',
            ], 400);
        }

        // Catat waktu presensi
        $waktuPresensi = Carbon::now();
        $batasHadir = Carbon::parse($qrCode->tanggal . ' ' . $qrCode->batas_hadir);
        $batasTerlambat = Carbon::parse($qrCode->tanggal . ' ' . $qrCode->batas_terlambat);
        $durasiMaksimal = Carbon::parse($qrCode->tanggal . ' ' . $qrCode->waktu_mulai)
            ->diffInMinutes($batasTerlambat);

        // Cek apakah sudah melewati batas maksimal dari input menit
        if ($waktuPresensi->greaterThan($batasTerlambat)) {
            return response()->json([
                'success' => false,
                'message' => "QR Code sudah tidak valid. Waktu presensi telah berakhir (lebih dari {$durasiMaksimal} menit).",
            ], 400);
        }

        // Tentukan status
        $status = 'hadir';
        $keterangan = 'Hadir tepat waktu';

        if ($waktuPresensi->greaterThan($batasHadir)) {
            // Lewat batas hadir sampai batas maksimal = terlambat
            $status = 'terlambat';
            $menitTerlambat = $waktuPresensi->diffInMinutes($batasHadir);
            $keterangan = "Terlambat {$menitTerlambat} menit";
        }

        // Simpan presensi
        Presensi::create([
            'qr_code_id' => $qrCode->id,
            'siswa_nis' => session('siswa_nis'),
            'siswa_nama' => session('siswa_nama'),
            'waktu_presensi' => $waktuPresensi,
            'status' => $status,
            'keterangan' => $keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat!',
            'data' => [
                'status' => $status,
                'keterangan' => $keterangan,
                'waktu' => $waktuPresensi->format('H:i:s'),
            ],
        ]);
    }

    /**
     * Deactivate QR Code
     */
    public function deactivate($id)
    {
        $qrCode = QrCode::findOrFail($id);
        $qrCode->update(['is_active' => false]);

        return redirect()->back()->with('success', 'QR Code berhasil dinonaktifkan.');
    }
}
