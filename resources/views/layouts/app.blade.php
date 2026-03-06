<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>

    <div class="sidebar p-3 text-dark">

    <h5 class="mb-4 fw-bold text-dark">
        Student Attendance
    </h5>

    @if(session('admin_logged_in'))
        {{-- Menu untuk Admin --}}
        <a href="{{ route('admin.dashboard') }}" class="nav-link text-dark">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('admin.data-siswa') }}" class="nav-link text-dark">
            <i class="bi bi-people me-2"></i>Data Siswa</a>
        <a href="{{ route('admin.laporan') }}" class="nav-link text-dark">
            <i class="bi bi-file-earmark-text me-2"></i>Laporan</a>
        <a href="#" class="nav-link text-dark">
            <i class="bi bi-gear me-2"></i>Pengaturan</a>
    @else
        {{-- Menu untuk Siswa --}}
        <a href="{{ route('home') }}" class="nav-link text-dark">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('presensi.index') }}" class="nav-link text-dark">
            <i class="bi bi-calendar-check me-2"></i>Data Presensi</a>
        <a href="{{ route('laporan.index') }}" class="nav-link text-dark">
            <i class="bi bi-file-earmark-text me-2"></i>Laporan</a>
        <a href="{{ route('profile.index') }}" class="nav-link text-dark">
            <i class="bi bi-person-circle me-2"></i>Profil</a>
    @endif

</div>

    <div class="main">
        <div class="topbar">
            <h1>@yield('title')</h1>
        </div>
    <div class="content">
        @yield('content')
    </div>

    @include('components.footer')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
