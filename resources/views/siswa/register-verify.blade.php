<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Siswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-100 via-white to-blue-50">
<div class="min-h-screen flex items-center justify-center px-3 sm:px-4 py-6 sm:py-10">
    <div class="w-full max-w-xl bg-white border border-blue-100 shadow-xl shadow-blue-100/60 rounded-2xl p-4 sm:p-7">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Verifikasi Email</h1>
                <p class="text-sm text-gray-600 mt-1">Langkah terakhir: masukkan kode 6 digit dari email Anda.</p>
            </div>
            <div class="inline-flex items-center gap-2 text-xs sm:text-sm">
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">1 Biodata</span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">2 Akun</span>
                <span class="px-3 py-1 rounded-full bg-blue-600 text-white font-semibold">3 Verifikasi</span>
            </div>
        </div>

        <div class="mt-4 rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            Jika email belum masuk, cek folder spam lalu klik Kirim ulang kode.
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

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-green-300 bg-green-50 text-green-700 p-4">{{ session('success') }}</div>
        @endif

        @if (session('mail_warning'))
            <div class="mt-4 rounded-lg border border-amber-300 bg-amber-50 text-amber-800 p-4 text-sm">
                {{ session('mail_warning') }}
            </div>
        @endif

        @if (session('otp_preview'))
            <div class="mt-4 rounded-lg border border-blue-300 bg-blue-50 text-blue-900 p-4">
                <p class="text-xs sm:text-sm">OTP Preview (mode lokal):</p>
                <p class="text-2xl font-bold tracking-[0.25em] mt-1">{{ session('otp_preview') }}</p>
            </div>
        @endif

        @php
            $inputClass = 'mt-1 w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm sm:text-base text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition';
        @endphp

        <form id="verifyForm" method="POST" action="{{ route('siswa.register.verify.store') }}" class="mt-6 space-y-4 sm:space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" required class="{{ $inputClass }} bg-blue-50" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kode Verifikasi</label>
                <input type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus class="{{ $inputClass }} tracking-[0.2em]" placeholder="123456" title="Kode verifikasi harus 6 digit angka.">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full sm:w-auto rounded-md bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 font-medium shadow-lg shadow-blue-200">Verifikasi Akun</button>
            </div>
        </form>

        <form method="POST" action="{{ route('siswa.register.verify.resend') }}" class="mt-3">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $email) }}">
            <button type="submit" class="text-sm font-medium text-blue-700 hover:text-blue-900">Kirim ulang kode</button>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('verifyForm');
    if (!form) {
        return;
    }

    const codeInput = form.querySelector('input[name="code"]');

    function setInputState(input) {
        if (input.validationMessage) {
            input.classList.add('border-red-400', 'ring-2', 'ring-red-100');
            input.classList.remove('border-blue-200', 'ring-blue-100');
        } else {
            input.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
            input.classList.add('border-blue-200');
        }
    }

    codeInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
        this.setCustomValidity(this.value.length === 6 ? '' : 'Kode verifikasi harus tepat 6 digit.');
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
