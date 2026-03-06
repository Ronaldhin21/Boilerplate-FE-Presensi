@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Real-time Clock -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 bg-primary text-white">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock fs-4 me-3"></i>
                        <div>
                            <h5 class="mb-0" id="realtime-clock">--:--:--</h5>
                            <small id="realtime-date">-- --, ----</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">

   <div class="col-md-3">
    <a href="{{ route('scan.qr') }}" class="text-decoration-none">
        <div class="card shadow-sm border-0 rounded-4 card-clickable">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 fs-2 text-primary">
                    <i class="bi bi-qr-code-scan"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-dark">Scan QR Code</h6>
                    <small class="text-muted">Presensi Hari Ini</small>
                </div>
            </div>
        </div>
    </a>
</div>


        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6>Status Kehadiran</h6>
                    <small class="text-danger">Belum melakukan absensi</small>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center">
                    <h6>Jumlah Hadir</h6>
                    <h2 class="fw-bold text-success"></h2>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center">
                    <h6>Jumlah Tidak Hadir</h6>
                    <h2 class="fw-bold text-danger"></h2>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Riwayat Absen Bocah -->
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">

        <h5 class="mb-3">Riwayat Presensi</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01 Februari 2026</td>
                        <td>06:47</td>
                        <td><span class="badge bg-success">Hadir</span></td>
                    </tr>
                    <tr>
                        <td>01 Februari 2026</td>
                        <td>07:30</td>
                        <td><span class="badge bg-warning text-dark">Terlambat</span></td>
                    </tr>
                    <tr>
                        <td>01 Februari 2026</td>
                        <td>-</td>
                        <td><span class="badge bg-danger">Tidak Hadir</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    // Real-time clock function
    function updateClock() {
        const now = new Date();

        // Nama hari dalam bahasa Indonesia
        const hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Format waktu HH:MM:SS
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        const waktu = `${jam}:${menit}:${detik}`;

        // Format tanggal
        const hari = hariIndo[now.getDay()];
        const tanggal = now.getDate();
        const bulan = bulanIndo[now.getMonth()];
        const tahun = now.getFullYear();
        const tanggalLengkap = `${hari}, ${tanggal} ${bulan} ${tahun}`;

        // Update DOM
        document.getElementById('realtime-clock').textContent = waktu;
        document.getElementById('realtime-date').textContent = tanggalLengkap;
    }

    // Update setiap detik
    updateClock(); // Panggil pertama kali
    setInterval(updateClock, 1000); // Update setiap 1 detik
</script>

@endsection
