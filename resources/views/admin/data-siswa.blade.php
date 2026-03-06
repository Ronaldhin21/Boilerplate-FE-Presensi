@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<style>
    .class-separator {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border-top: 3px solid #4338ca;
        border-bottom: 3px solid #4338ca;
    }
    .class-separator td {
        background: transparent !important;
    }
    .class-separator td > div {
        color: #ffffff !important;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    .class-separator td > div > div {
        color: #ffffff !important;
    }
    .class-separator strong {
        color: #ffffff !important;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    .class-separator i {
        color: #fbbf24 !important;
    }
</style>

<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Data Siswa</h2>
            <p class="text-muted">Kelola data siswa kelas 10, 11, dan 12</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Upload Data Siswa -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0"><i class="bi bi-upload me-2"></i>Upload Data Siswa</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.data-siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="kelas" class="form-control"
                                       placeholder="10, 11, 12..." required maxlength="10">
                                <small class="text-muted">Contoh: 10, 11, 12</small>
                            </div>
                            <div class="col-md-4">
                                <label for="rombel" class="form-label">Rombel</label>
                                <input type="text" name="rombel" id="rombel" class="form-control"
                                       placeholder="A, B, C, D..." required maxlength="10">
                                <small class="text-muted">Contoh: A, IPA-1, TKJ</small>
                            </div>
                            <div class="col-md-4">
                                <label for="file" class="form-label">File CSV</label>
                                <input type="file" name="file" id="file" class="form-control"
                                       accept=".csv,.txt" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-2"></i>Upload Data
                            </button>
                            <a href="{{ route('admin.data-siswa.template') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-download me-2"></i>Download Template CSV
                            </a>
                        </div>
                    </form>
                    <div class="alert alert-warning mt-3 mb-0">
                        <small>
                            <strong>⚠️ PENTING - Cara Upload Data:</strong>
                            <br><br>• <strong>Upload 1 file = 1 kelas + 1 rombel</strong>
                            <br>• Jika punya data kelas 11A, 11B, 11C → Upload 3x (pisah file atau upload berulang dengan input kelas/rombel berbeda)
                            <br>• Database akan mengikuti struktur file CSV yang Anda upload
                            <br>• Semua kolom dari CSV akan disimpan apa adanya
                            <br>• File maksimal 2MB per upload
                            <br><br><em>Contoh: File siswa_11c.csv → Input Kelas: 11, Rombel: C → Klik Upload</em>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0">Statistik</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $totalSiswa = \App\Models\Siswa::count();
                        $kelas10 = \App\Models\Siswa::where('kelas', 'LIKE', '%10%')->count();
                        $kelas11 = \App\Models\Siswa::where('kelas', 'LIKE', '%11%')->count();
                        $kelas12 = \App\Models\Siswa::where('kelas', 'LIKE', '%12%')->count();
                    @endphp
                    <div class="mb-3">
                        <h3 class="mb-0">{{ $totalSiswa }}</h3>
                        <small class="text-muted">Total Siswa</small>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kelas 10</span>
                        <strong>{{ $kelas10 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kelas 11</span>
                        <strong>{{ $kelas11 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kelas 12</span>
                        <strong>{{ $kelas12 }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.data-siswa') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="filter_kelas" class="form-label">Kelas</label>
                        <select name="kelas" id="filter_kelas" class="form-select">
                            <option value="">Semua</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                                    {{ $k }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filter_rombel" class="form-label">Rombel</label>
                        <select name="rombel" id="filter_rombel" class="form-select">
                            <option value="">Semua</option>
                            @foreach($rombelList as $r)
                                <option value="{{ $r }}" {{ request('rombel') == $r ? 'selected' : '' }}>
                                    {{ $r }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Cari data siswa... (tekan Enter)" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="per_page" class="form-label">Tampilkan</label>
                        <select name="per_page" id="per_page" class="form-select">
                            <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100</option>
                            <option value="250" {{ request('per_page') == 250 ? 'selected' : '' }}>250</option>
                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search me-2"></i>Cari Data
                        </button>
                    </div>
                </div>
                @if(request('kelas') || request('rombel') || request('search'))
                    <div class="mt-3">
                        <a href="{{ route('admin.data-siswa') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Siswa</h5>
                <span class="badge bg-primary">{{ $siswas->total() }} Siswa</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">No</th>
                            @if($siswas->count() > 0)
                                @php
                                    // Ambil kolom dari data JSON siswa pertama
                                    $firstSiswa = $siswas->first();
                                    $columns = $firstSiswa && $firstSiswa->data ? array_keys($firstSiswa->data) : [];
                                @endphp
                                @foreach($columns as $col)
                                    <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                                @endforeach
                            @endif
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $prevKelas = null;
                            $prevRombel = null;
                        @endphp
                        @forelse($siswas as $index => $siswa)
                            @php
                                // Cek apakah ada perubahan kelas atau rombel
                                $showSeparator = ($prevKelas !== null &&
                                                 ($siswa->kelas != $prevKelas || $siswa->rombel != $prevRombel));
                                $isFirstRow = ($index == 0);
                                $prevKelas = $siswa->kelas;
                                $prevRombel = $siswa->rombel;
                            @endphp

                            @if($isFirstRow)
                                {{-- Separator untuk grup pertama --}}
                                <tr class="class-separator">
                                    <td colspan="{{ 1 + count($columns) + 1 }}" class="py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                                <strong>KELAS {{ $siswa->kelas }} - ROMBEL {{ $siswa->rombel }}</strong>
                                                <i class="bi bi-grid-3x3-gap-fill ms-2"></i>
                                            </div>
                                            <form action="{{ route('admin.data-siswa.destroy-bulk', [$siswa->kelas, $siswa->rombel]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('⚠️ HAPUS SEMUA DATA KELAS {{ $siswa->kelas }} ROMBEL {{ $siswa->rombel }}?\n\nSemua siswa di kelas ini akan dihapus permanen!\n\nKlik OK untuk melanjutkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash3-fill me-1"></i>Hapus Semua Kelas Ini
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @elseif($showSeparator)
                                {{-- Separator row untuk grup berikutnya --}}
                                <tr class="class-separator">
                                    <td colspan="{{ 1 + count($columns) + 1 }}" class="py-3 px-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                                <strong>KELAS {{ $siswa->kelas }} - ROMBEL {{ $siswa->rombel }}</strong>
                                                <i class="bi bi-grid-3x3-gap-fill ms-2"></i>
                                            </div>
                                            <form action="{{ route('admin.data-siswa.destroy-bulk', [$siswa->kelas, $siswa->rombel]) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('⚠️ HAPUS SEMUA DATA KELAS {{ $siswa->kelas }} ROMBEL {{ $siswa->rombel }}?\n\nSemua siswa di kelas ini akan dihapus permanen!\n\nKlik OK untuk melanjutkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash3-fill me-1"></i>Hapus Semua Kelas Ini
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="px-4">{{ $siswas->firstItem() + $index }}</td>
                                @foreach($columns as $col)
                                    <td>
                                        @php
                                            $value = $siswa->data[$col] ?? '-';
                                            // Limit panjang text
                                            $displayValue = strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value;
                                        @endphp
                                        {{ $displayValue }}
                                    </td>
                                @endforeach
                                <td class="text-center">
                                    <form action="{{ route('admin.data-siswa.destroy', $siswa->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Hapus data siswa ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p class="mb-0">Belum ada data siswa</p>
                                    <small>Upload file CSV untuk menambahkan data</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($siswas instanceof \Illuminate\Pagination\LengthAwarePaginator && $siswas->hasPages())
            <div class="card-footer bg-white border-0 pb-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $siswas->firstItem() ?? 0 }} - {{ $siswas->lastItem() ?? 0 }}
                            dari {{ $siswas->total() }} data
                        </small>
                    </div>
                    <div>
                        {{ $siswas->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>

<script>
// Enable Enter key to submit search form
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.form.submit();
            }
        });
    }
});
</script>

@endsection
