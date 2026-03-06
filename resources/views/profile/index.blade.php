@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="container-fluid">

    <div class="row g-4">

        <!-- Card Biodata -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">

                    <!-- Header -->
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://i.natgeofe.com/k/5e4ea67e-2219-4de4-9240-2992faef0cb6/trump-portrait_2x3.jpg" 
                             class="rounded-circle me-3" 
                             width="80">
                        <div>
                            <h5 class="mb-0">Nama Lengkap</h5>
                            <small class="text-muted">@username</small><br>
                            <span class="badge bg-primary-subtle text-primary">
                                Student
                            </span>
                        </div>
                    </div>

                    <!-- Biodata -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">NIS</small>
                            <p class="fw-semibold">000000</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Admission Date</small>
                            <p class="fw-semibold">00-00-0000</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">First Name</small>
                            <p class="fw-semibold">First</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Last Name</small>
                            <p class="fw-semibold">Last</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Father Name</small>
                            <p class="fw-semibold">Ayah</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Mother Name</small>
                            <p class="fw-semibold">Ibu</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Date of Birth</small>
                            <p class="fw-semibold">00-00-0000</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Religion</small>
                            <p class="fw-semibold">-</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Class</small>
                            <p class="fw-semibold">XI</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Fase</small>
                            <p class="fw-semibold">E</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Card Contact Information -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">

                    <h5 class="mb-4">
                        <i class="bi bi-person-lines-fill me-2"></i>
                        Contact Information
                    </h5>

                    <div class="mb-3">
                        <small class="text-muted">Phone Number</small>
                        <p class="fw-semibold">+62 0000 0000 0000</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <p class="fw-semibold">email@example.com</p>
                    </div>

                    <div>
                        <small class="text-muted">Address</small>
                        <p class="fw-semibold">
                            Alamat lengkap siswa di sini.
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection