<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa - Akun</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-100 via-white to-blue-50">
<div class="min-h-screen flex items-center justify-center px-3 sm:px-4 py-6 sm:py-10">
    <div class="w-full max-w-xl bg-white border border-blue-100 shadow-xl shadow-blue-100/60 rounded-2xl p-4 sm:p-7">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Registrasi Siswa</h1>
                <p class="text-sm text-gray-600 mt-1">Langkah 2 dari 2: buat akun untuk login.</p>
            </div>
            <div class="inline-flex items-center gap-2 text-xs sm:text-sm">
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">1 Biodata</span>
                <span class="px-3 py-1 rounded-full bg-blue-600 text-white font-semibold">2 Akun</span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-500 font-semibold">3 Verifikasi</span>
            </div>
        </div>

        <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            Email akan dipakai sebagai username, lalu sistem akan kirim kode verifikasi ke email tersebut.
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

        <form id="accountForm" method="POST" action="{{ route('siswa.register.account.store') }}" class="mt-6 space-y-4 sm:space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Email (Username)</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="{{ $inputClass }}" placeholder="nama@email.com" autocomplete="email">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="{{ $inputClass }}" placeholder="Minimal 8 karakter" minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d).{8,}$" title="Password minimal 8 karakter dan wajib kombinasi huruf dan angka." autocomplete="new-password">
                <p class="mt-1 text-xs text-gray-500">Gunakan kombinasi huruf dan angka agar akun lebih aman.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="{{ $inputClass }}" placeholder="Ulangi password yang sama" autocomplete="new-password">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full sm:w-auto rounded-md bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 font-medium shadow-lg shadow-blue-200">Kirim Kode Verifikasi</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('accountForm');
    if (!form) {
        return;
    }

    const passwordInput = form.querySelector('input[name="password"]');
    const confirmInput = form.querySelector('input[name="password_confirmation"]');

    function setInputState(input) {
        if (input.validationMessage) {
            input.classList.add('border-red-400', 'ring-2', 'ring-red-100');
            input.classList.remove('border-blue-200', 'ring-blue-100');
        } else {
            input.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
            input.classList.add('border-blue-200');
        }
    }

    function validatePasswordMatch() {
        if (confirmInput.value && passwordInput.value !== confirmInput.value) {
            confirmInput.setCustomValidity('Konfirmasi password harus sama.');
        } else {
            confirmInput.setCustomValidity('');
        }

        const strong = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(passwordInput.value);
        passwordInput.setCustomValidity(strong ? '' : 'Password minimal 8 karakter dan kombinasi huruf + angka.');

        setInputState(passwordInput);
        setInputState(confirmInput);
    }

    passwordInput.addEventListener('input', validatePasswordMatch);
    confirmInput.addEventListener('input', validatePasswordMatch);

    form.addEventListener('submit', function (event) {
        validatePasswordMatch();
        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
        }
    });
});
</script>
</body>
</html>
