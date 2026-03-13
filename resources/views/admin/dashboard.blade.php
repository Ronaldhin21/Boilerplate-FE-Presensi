@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 fs-2 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Total Siswa</h6>
                        <h4 class="mb-0">150</h4>
                        <small class="text-muted">Siswa Aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 fs-2 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Hadir Hari Ini</h6>
                        <h4 class="mb-0">142</h4>
                        <small class="text-muted">94.7%</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 fs-2 text-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Terlambat</h6>
                        <h4 class="mb-0">5</h4>
                        <small class="text-muted">3.3%</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 fs-2 text-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Tidak Hadir</h6>
                        <h4 class="mb-0">3</h4>
                        <small class="text-muted">2%</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0">Generate QR Code Presensi</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('qr_code'))
                        <div class="alert alert-success">
                            <strong>QR Code berhasil dibuat!</strong>
                            <a href="{{ route('admin.qr.show', session('qr_code')->id) }}" class="btn btn-sm btn-primary ms-2" target="_blank">
                                Lihat QR Code
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('admin.qr.generate') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="hari" class="form-label">Hari</label>
                                <select name="hari" id="hari" class="form-select" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                                @error('hari')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                       value="{{ date('Y-m-d') }}" required>
                                @error('tanggal')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="waktu_mulai" class="form-label">Waktu Mulai Presensi</label>
                                <div class="form-control bg-light" id="waktu_mulai_display">{{ date('H:i') }}</div>
                                <small class="text-muted">Otomatis mengikuti jam realtime saat ini</small>
                                <input type="hidden" name="waktu_mulai" id="waktu_mulai" value="{{ date('H:i') }}" required>
                                @error('waktu_mulai')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="durasi_hadir" class="form-label">Durasi Hadir (menit)</label>
                                <input type="number" name="durasi_hadir" id="durasi_hadir" class="form-control" min="1" max="120" value="30" required>
                                @error('durasi_hadir')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="durasi_maksimal" class="form-label">Durasi Maksimal (menit)</label>
                                <input type="number" name="durasi_maksimal" id="durasi_maksimal" class="form-control" min="1" max="180" value="60" required>
                                @error('durasi_maksimal')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-info mb-0">
                                    <strong>Durasi Presensi (Input Menit)</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>0 s/d Durasi Hadir dari waktu mulai = <span class="badge bg-success">Hadir Tepat Waktu</span></li>
                                        <li>Di atas Durasi Hadir s/d Durasi Maksimal = <span class="badge bg-warning">Terlambat</span></li>
                                        <li>Lebih dari Durasi Maksimal = <span class="badge bg-danger">QR Code Tidak Valid</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-qr-code me-2"></i>Generate QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0">QR Code Aktif</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $qrCodes = \App\Models\QrCode::where('is_active', true)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($qrCodes->count() > 0)
                        <div class="list-group">
                            @foreach($qrCodes as $qr)
                                @php
                                    $tanggalOnly = \Carbon\Carbon::parse($qr->tanggal)->toDateString();
                                    $waktuMulai = \Carbon\Carbon::parse($tanggalOnly . ' ' . $qr->waktu_mulai);
                                    $batasTerlambat = \Carbon\Carbon::parse($tanggalOnly . ' ' . $qr->batas_terlambat);
                                    $durasi = $waktuMulai->diffInMinutes($batasTerlambat);
                                @endphp
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $qr->hari }}</h6>
                                            <small class="text-muted d-block">
                                                {{ \Carbon\Carbon::parse($qr->tanggal)->format('d/m/Y') }}
                                                - {{ \Carbon\Carbon::parse($qr->waktu_mulai)->format('H:i') }}
                                            </small>
                                            <small class="text-primary">
                                                <i class="bi bi-clock"></i> Durasi: {{ $durasi }} menit
                                            </small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.qr.show', $qr->id) }}"
                                               class="btn btn-outline-primary btn-sm" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.qr.deactivate', $qr->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Nonaktifkan QR Code ini?')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada QR Code aktif</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0">Menu Admin</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.laporan') }}" class="text-decoration-none">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-text fs-1 text-primary mb-3"></i>
                                        <h6 class="mb-0">Laporan Presensi</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.data-siswa') }}" class="text-decoration-none">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people fs-1 text-success mb-3"></i>
                                        <h6 class="mb-0">Data Siswa</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-gear fs-1 text-warning mb-3"></i>
                                        <h6 class="mb-0">Pengaturan</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <form method="POST" action="{{ url('/admin/logout') }}">
                                @csrf
                                <button type="submit" class="w-100 text-decoration-none border-0 bg-transparent p-0">
                                    <div class="card border shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <i class="bi bi-box-arrow-right fs-1 text-danger mb-3"></i>
                                            <h6 class="mb-0">Logout</h6>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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

        const waktuMulaiInput = document.getElementById('waktu_mulai');
        if (waktuMulaiInput) {
            waktuMulaiInput.value = `${jam}:${menit}`;
        }

        const waktuMulaiDisplay = document.getElementById('waktu_mulai_display');
        if (waktuMulaiDisplay) {
            waktuMulaiDisplay.textContent = `${jam}:${menit}`;
        }

    }

    // Update setiap detik
    updateClock(); // Panggil pertama kali
    setInterval(updateClock, 1000); // Update setiap 1 detik
</script>

@endsection
