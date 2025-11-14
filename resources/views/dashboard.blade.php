@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">

    <!-- HEADER DASHBOARD -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </h2>
            <p class="text-muted mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
        </div>
        <!-- TOMBOL BUKA MODAL -->
        <button type="button" class="btn btn-success btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahTamuModal">
            <i class="fas fa-plus"></i> Tambah Tamu
        </button>

        <!-- MODAL FORM -->
        <div class="modal fade" id="tambahTamuModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle"></i> Tambah Tamu Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('tamu.form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIK CARD -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-users fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $tamu->total() }}</h5>
                        <small>Total Tamu</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-calendar-day fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $tamu->where('created_at', '>=', now()->startOfDay())->count() }}</h5>
                        <small>Hari Ini</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-clock fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $tamu->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</h5>
                        <small>Minggu Ini</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-download fa-2x me-3"></i>
                    <div>
                        <a href="{{ route('admin.csv') }}" class="text-white text-decoration-none">
                            <h5 class="mb-0">Export</h5>
                            <small>CSV</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM TAMBAH TAMU CEPAT -->
    {{-- <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle"></i> Tambah Tamu Baru (Cepat)
            </h5>
        </div>
        <div class="card-body">
            @include('tamu.form') <!-- Form sama seperti /isi -->
        </div>
    </div> --}}

    <!-- TABEL DAFTAR TAMU -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tamu Terbaru</h5>
            <a href="/admin" class="btn btn-light btn-sm">
                <i class="fas fa-list"></i> Lihat Semua
            </a>
        </div>
        <div class="card-body p-0">
            @include('tamu.table')
        </div>
    </div>
</div>
@endsection