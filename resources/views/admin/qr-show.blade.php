@extends('layouts.app')

@section('title', 'QR Code Presensi')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 text-center">
                    <h4 class="mb-0">QR Code Presensi</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <!-- QR Code Display -->
                    <div class="bg-white p-4 border rounded-3 mb-4 d-inline-block">
                        @php
                            use BaconQrCode\Renderer\ImageRenderer;
                            use BaconQrCode\Renderer\Image\SvgImageBackEnd;
                            use BaconQrCode\Renderer\RendererStyle\RendererStyle;
                            use BaconQrCode\Writer;

                            $renderer = new ImageRenderer(
                                new RendererStyle(300),
                                new SvgImageBackEnd()
                            );
                            $writer = new Writer($renderer);
                            $qrCodeSvg = $writer->writeString($qrCode->code);
                        @endphp
                        {!! $qrCodeSvg !!}
                    </div>

                    <p class="mb-4">
                        <strong>Kode Barcode:</strong> {{ $qrCode->code }}
                    </p>

                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <form action="{{ route('admin.qr.deactivate', $qrCode->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Nonaktifkan QR Code ini?')">
                                <i class="bi bi-x-circle me-2"></i>Nonaktifkan QR
                            </button>
                        </form>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="bi bi-printer me-2"></i>Cetak
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Presensi -->
            <div class="card shadow-sm border-0 rounded-4 mt-3">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0">Statistik Presensi</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $totalPresensi = $qrCode->presensis->count();
                        $hadir = $qrCode->presensis->where('status', 'hadir')->count();
                        $terlambat = $qrCode->presensis->where('status', 'terlambat')->count();
                        $tidakHadir = $qrCode->presensis->where('status', 'tidak_hadir')->count();
                    @endphp

                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="mb-1">{{ $totalPresensi }}</h3>
                                <small class="text-muted">Total</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="mb-1 text-success">{{ $hadir }}</h3>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="mb-1 text-warning">{{ $terlambat }}</h3>
                                <small class="text-muted">Terlambat</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="mb-1 text-danger">{{ $tidakHadir }}</h3>
                                <small class="text-muted">Tidak Hadir</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .card-header, .alert, .navbar, footer {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

@endsection
