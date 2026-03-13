<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('rombel_kelas')) {
            $query->where('rombel_kelas', $request->rombel_kelas);
        }

        if ($request->filled('search')) {
            $query->search((string) $request->search);
        }

        $siswas = $query->orderByRaw('CAST(kelas AS INTEGER)')
            ->orderBy('rombel_kelas')
            ->orderBy('first_name')
            ->paginate(100)
            ->withQueryString();

        $kelasList = Siswa::query()
            ->distinct()
            ->whereNotNull('kelas')
            ->orderByRaw('CAST(kelas AS INTEGER)')
            ->pluck('kelas');

        $rombelList = Siswa::query()
            ->distinct()
            ->whereNotNull('rombel_kelas')
            ->orderBy('rombel_kelas')
            ->pluck('rombel_kelas');

        return view('admin.data-siswa', compact('siswas', 'kelasList', 'rombelList'));
    }

    public function import()
    {
        return redirect()->back()->with('error', 'Import CSV dinonaktifkan. Data siswa sekarang otomatis dari registrasi siswa.');
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|string|max:50|unique:siswas,nis,'.$id,
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
            'email' => 'required|email|max:150|unique:siswas,email,'.$id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $siswa = Siswa::findOrFail($id);
        $siswa->update($validator->validated());

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        Siswa::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function destroyByKelasRombel(string $kelas, string $rombel)
    {
        $count = Siswa::where('kelas', $kelas)
            ->where('rombel_kelas', $rombel)
            ->count();

        if ($count < 1) {
            return redirect()->back()->with('error', 'Tidak ada data siswa pada kelas/rombel tersebut.');
        }

        Siswa::where('kelas', $kelas)
            ->where('rombel_kelas', $rombel)
            ->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus '.$count.' data siswa.');
    }

    public function downloadTemplate()
    {
        return redirect()->back()->with('error', 'Template CSV tidak diperlukan karena input siswa melalui registrasi mandiri.');
    }
}
