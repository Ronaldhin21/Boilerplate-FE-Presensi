@extends('layouts.app')

@section('title', 'Presensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">

    <h5 class="mb-0">Data Presensi</h5>

    <form method="GET" action="{{ route('dashboard') }}">
        <div class="input-group">
            <input type="date" 
                   name="tanggal" 
                   class="form-control"
                   value="{{ request('tanggal') }}">
            <button class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

</div>
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

@endsection