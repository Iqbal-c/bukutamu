@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- NOTIFIKASI SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Judul -->
    <h3 class="mb-4 text-primary">Daftar Tamu Hari Ini</h3>
    
    <!-- Tabel -->
    @include('tamu.table') <!-- Tabel TANPA tombol Edit/Hapus -->
</div>
@endsection