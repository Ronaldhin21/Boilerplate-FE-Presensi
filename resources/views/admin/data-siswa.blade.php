@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">Data Siswa</h2>
            <p class="text-muted">Data siswa otomatis dari registrasi mandiri siswa.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.data-siswa') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="filter_kelas" class="form-label">Kelas</label>
                        <select name="kelas" id="filter_kelas" class="form-select">
                            <option value="">Semua</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filter_rombel" class="form-label">Rombel</label>
                        <select name="rombel_kelas" id="filter_rombel" class="form-select">
                            <option value="">Semua</option>
                            @foreach($rombelList as $r)
                                <option value="{{ $r }}" {{ request('rombel_kelas') == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Cari NIS, nama, orang tua, alamat, telepon, email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Siswa Register</h5>
            <span class="badge bg-primary">{{ $siswas->total() }} Siswa</span>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Ayah</th>
                    <th>Ibu</th>
                    <th>TTL</th>
                    <th>Agama</th>
                    <th>Kelas</th>
                    <th>Rombel</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Password Register</th>
                    <th>Verifikasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($siswas as $index => $siswa)
                    <tr>
                        <td>{{ $siswas->firstItem() + $index }}</td>
                        <td>{{ $siswa->nis }}</td>
                        <td>{{ $siswa->nama_lengkap }}</td>
                        <td>{{ $siswa->father_name }}</td>
                        <td>{{ $siswa->mother_name }}</td>
                        <td>{{ $siswa->place_of_birth }}, {{ optional($siswa->date_of_birth)->format('d-m-Y') }}</td>
                        <td>{{ $siswa->religion }}</td>
                        <td>{{ $siswa->kelas }}</td>
                        <td>{{ $siswa->rombel_kelas }}</td>
                        <td>{{ $siswa->alamat }}</td>
                        <td>{{ $siswa->nomor_telepon }}</td>
                        <td>{{ $siswa->email }}</td>
                        <td>
                            @if($siswa->password_register)
                                <code>{{ $siswa->password_register }}</code>
                            @else
                                <span class="text-muted">Tidak tersedia</span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->email_verified_at)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSiswaModal{{ $siswa->id }}">
                                Edit
                            </button>

                            <form action="{{ route('admin.data-siswa.destroy', $siswa->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus data siswa ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center py-4 text-muted">Belum ada data siswa register.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @foreach($siswas as $siswa)
            <div class="modal fade" id="editSiswaModal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.data-siswa.update', $siswa->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label">NIS</label><input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}" required></div>
                                    <div class="col-md-6"><label class="form-label">First Name</label><input type="text" name="first_name" class="form-control" value="{{ $siswa->first_name }}" required></div>
                                    <div class="col-md-6"><label class="form-label">Last Name</label><input type="text" name="last_name" class="form-control" value="{{ $siswa->last_name }}"></div>
                                    <div class="col-md-6"><label class="form-label">Father Name</label><input type="text" name="father_name" class="form-control" value="{{ $siswa->father_name }}" required></div>
                                    <div class="col-md-6"><label class="form-label">Mother Name</label><input type="text" name="mother_name" class="form-control" value="{{ $siswa->mother_name }}" required></div>
                                    <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input type="text" name="place_of_birth" class="form-control" value="{{ $siswa->place_of_birth }}" required></div>
                                    <div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="date_of_birth" class="form-control" value="{{ optional($siswa->date_of_birth)->format('Y-m-d') }}" required></div>
                                    <div class="col-md-6"><label class="form-label">Agama</label><input type="text" name="religion" class="form-control" value="{{ $siswa->religion }}" required></div>
                                    <div class="col-md-3"><label class="form-label">Kelas</label><input type="text" name="kelas" class="form-control" value="{{ $siswa->kelas }}" required></div>
                                    <div class="col-md-3"><label class="form-label">Rombel</label><input type="text" name="rombel_kelas" class="form-control" value="{{ $siswa->rombel_kelas }}" required></div>
                                    <div class="col-md-6"><label class="form-label">Telepon</label><input type="text" name="nomor_telepon" class="form-control" value="{{ $siswa->nomor_telepon }}" required></div>
                                    <div class="col-md-12"><label class="form-label">Alamat</label><input type="text" name="alamat" class="form-control" value="{{ $siswa->alamat }}" required></div>
                                    <div class="col-md-12"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ $siswa->email }}" required></div>
                                    <div class="col-md-12">
                                        <label class="form-label">Password Register (readonly)</label>
                                        <input type="text" class="form-control" value="{{ $siswa->password_register ?? 'Tidak tersedia' }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card-footer bg-white border-0 pb-4 px-4">
            {{ $siswas->links() }}
        </div>
    </div>
</div>
@endsection
