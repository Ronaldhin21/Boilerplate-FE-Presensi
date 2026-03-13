<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa - Biodata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-100 via-white to-blue-50">
<div class="min-h-screen py-6 sm:py-10 px-3 sm:px-4">
    <div class="max-w-5xl mx-auto bg-white border border-blue-100 shadow-xl shadow-blue-100/60 rounded-2xl p-4 sm:p-6 md:p-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Registrasi Siswa</h1>
                <p class="text-sm text-gray-600 mt-1">Langkah 1 dari 2: isi biodata siswa dengan benar.</p>
            </div>
            <div class="inline-flex items-center gap-2 text-xs sm:text-sm">
                <span class="px-3 py-1 rounded-full bg-blue-600 text-white font-semibold">1 Biodata</span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-500 font-semibold">2 Akun</span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-500 font-semibold">3 Verifikasi</span>
            </div>
        </div>

        <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            Gunakan data yang sama seperti dokumen sekolah agar validasi admin lebih mudah.
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-lg border border-red-300 bg-red-50 text-red-700 p-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 rounded-lg border border-red-300 bg-red-50 text-red-700 p-4">{{ session('error') }}</div>
        @endif

        @php
            $inputClass = 'mt-1 w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm sm:text-base text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition';
        @endphp

        <form id="biodataForm" method="POST" action="{{ route('siswa.register.biodata.store') }}" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">NIS</label>
                <input type="text" name="nis" value="{{ old('nis') }}" required autofocus class="{{ $inputClass }}" placeholder="Contoh: 20260001" inputmode="numeric" maxlength="20" pattern="[0-9]+" title="NIS hanya boleh angka.">
                <p class="mt-1 text-xs text-gray-500">Nomor induk siswa tanpa spasi tambahan.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="{{ $inputClass }}" placeholder="Nama depan siswa">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="{{ $inputClass }}" placeholder="Nama belakang (opsional)">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Father Name</label>
                <input type="text" name="father_name" value="{{ old('father_name') }}" required class="{{ $inputClass }}" placeholder="Nama ayah kandung">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mother Name</label>
                <input type="text" name="mother_name" value="{{ old('mother_name') }}" required class="{{ $inputClass }}" placeholder="Nama ibu kandung">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" required class="{{ $inputClass }}" placeholder="Contoh: Bandung">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="{{ $inputClass }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Agama</label>
                <input type="text" name="religion" value="{{ old('religion') }}" required class="{{ $inputClass }}" placeholder="Contoh: Islam">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" name="kelas" value="{{ old('kelas') }}" required class="{{ $inputClass }}" placeholder="10 / 11 / 12" inputmode="numeric" maxlength="2" pattern="(10|11|12)" title="Kelas harus 10, 11, atau 12.">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rombel Kelas</label>
                <input type="text" name="rombel_kelas" value="{{ old('rombel_kelas') }}" required class="{{ $inputClass }}" placeholder="A / IPA-1">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" rows="2" required class="{{ $inputClass }}" placeholder="Alamat lengkap siswa">{{ old('alamat') }}</textarea>
            </div>
            <div class="md:col-span-2 lg:col-span-1">
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required class="{{ $inputClass }}" placeholder="08xxxxxxxxxx" inputmode="tel" maxlength="15" pattern="^\+?[0-9]{10,15}$" title="Nomor telepon harus 10-15 digit angka, boleh diawali +.">
            </div>

            <div class="md:col-span-2 pt-2 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('login') }}" class="text-sm text-center sm:text-left text-gray-600 hover:text-blue-700">Kembali ke Login</a>
                <button type="submit" class="w-full sm:w-auto rounded-md bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 font-medium shadow-lg shadow-blue-200">Lanjut ke Pengaturan Akun</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('biodataForm');
    if (!form) {
        return;
    }

    const nisInput = form.querySelector('input[name="nis"]');
    const kelasInput = form.querySelector('input[name="kelas"]');
    const phoneInput = form.querySelector('input[name="nomor_telepon"]');

    function setInputState(input) {
        if (input.validationMessage) {
            input.classList.add('border-red-400', 'ring-2', 'ring-red-100');
            input.classList.remove('border-blue-200', 'ring-blue-100');
        } else {
            input.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
            input.classList.add('border-blue-200');
        }
    }

    nisInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        this.setCustomValidity(this.value.length < 4 ? 'NIS minimal 4 digit.' : '');
        setInputState(this);
    });

    kelasInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 2);
        const validKelas = ['10', '11', '12'];
        this.setCustomValidity(validKelas.includes(this.value) ? '' : 'Kelas hanya boleh 10, 11, atau 12.');
        setInputState(this);
    });

    phoneInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9+]/g, '');
        const valid = /^\+?[0-9]{10,15}$/.test(this.value);
        this.setCustomValidity(valid ? '' : 'Nomor telepon harus 10-15 digit angka.');
        setInputState(this);
    });

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
        }
    });
});
</script>
</body>
</html>
