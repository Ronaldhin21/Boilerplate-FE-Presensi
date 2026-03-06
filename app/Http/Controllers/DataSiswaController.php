<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataSiswaController extends Controller
{
    /**
     * Display data siswa dengan filter dan pencarian
     */
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter berdasarkan rombel
        if ($request->filled('rombel')) {
            $query->where('rombel', $request->rombel);
        }

        // Pencarian berdasarkan data JSON
        if ($request->filled('search')) {
            $search = $request->search;
            $query->searchInData($search);
        }

        // Pagination: 100 per page atau semua jika diminta
        $perPage = $request->get('per_page', 100);
        if ($perPage == 'all') {
            $siswas = $query->orderByRaw('CAST(kelas AS INTEGER)')
                            ->orderBy('rombel')
                            ->orderBy('id')
                            ->get();
            // Wrap in paginator-like object for view compatibility
            $siswas = new \Illuminate\Pagination\LengthAwarePaginator(
                $siswas,
                $siswas->count(),
                $siswas->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $siswas = $query->orderByRaw('CAST(kelas AS INTEGER)')
                            ->orderBy('rombel')
                            ->orderBy('id')
                            ->paginate($perPage)
                            ->withQueryString();
        }

        // Get distinct kelas dan rombel untuk filter - sorted numerically
        $kelasList = Siswa::distinct()
            ->whereNotNull('kelas')
            ->orderByRaw('CAST(kelas AS INTEGER)')
            ->pluck('kelas');
        $rombelList = Siswa::distinct()->whereNotNull('rombel')->pluck('rombel')->sort();

        return view('admin.data-siswa', compact('siswas', 'kelasList', 'rombelList'));
    }

    /**
     * Import data siswa dari file CSV
     * Database mengikuti struktur file CSV yang diupload
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'kelas' => 'required|string|max:10',
            'rombel' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();

            // Baca file CSV
            $csvData = array_map('str_getcsv', file($path));
            $header = array_shift($csvData); // Ambil header

            // Bersihkan header
            $header = array_map('trim', $header);

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($csvData as $rowIndex => $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Validasi jumlah kolom sesuai header
                if (count($row) !== count($header)) {
                    $errors[] = "Baris " . ($rowIndex + 2) . ": Jumlah kolom tidak sesuai";
                    continue;
                }

                // Combine header dengan data untuk membuat array asosiatif
                $rowData = array_combine($header, array_map('trim', $row));

                // Simpan data siswa dengan struktur dari CSV
                Siswa::create([
                    'kelas' => $request->kelas,
                    'rombel' => strtoupper($request->rombel),
                    'data' => $rowData, // Semua data CSV disimpan apa adanya
                ]);

                $imported++;
            }

            DB::commit();

            $message = "Berhasil import {$imported} data siswa ke Kelas {$request->kelas} Rombel {$request->rombel}";
            if (!empty($errors)) {
                $message .= ". " . count($errors) . " baris gagal: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " lainnya";
                }
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data siswa
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            return redirect()->back()->with('success', 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua data siswa berdasarkan kelas dan rombel
     */
    public function destroyByKelasRombel($kelas, $rombel)
    {
        try {
            $count = Siswa::where('kelas', $kelas)
                         ->where('rombel', $rombel)
                         ->count();

            if ($count == 0) {
                return redirect()->back()->with('error', 'Tidak ada data siswa Kelas ' . $kelas . ' Rombel ' . $rombel);
            }

            Siswa::where('kelas', $kelas)
                ->where('rombel', $rombel)
                ->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus ' . $count . ' data siswa Kelas ' . $kelas . ' Rombel ' . $rombel);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export template CSV
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_siswa.csv"',
        ];

        $columns = ['NIS', 'Nama', 'Jenis_Kelamin', 'Alamat', 'No_Telp', 'Email'];
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Contoh data
            fputcsv($file, ['2024001', 'Ahmad Rizki', 'L', 'Jl. Merdeka No. 1', '081234567890', 'ahmad@example.com']);
            fputcsv($file, ['2024002', 'Siti Nurhaliza', 'P', 'Jl. Sudirman No. 2', '081234567891', 'siti@example.com']);
            fputcsv($file, ['2024003', 'Budi Santoso', 'L', 'Jl. Gatot Subroto No. 3', '081234567892', 'budi@example.com']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
