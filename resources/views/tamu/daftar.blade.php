@extends('layouts.app')
@section('title', 'Daftar Tamu')

@section('content')
<div class="container py-4">

    <!-- NOTIFIKASI SUCCESS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg me-3"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="mb-1 text-primary"><i class="fas fa-list-ul me-2"></i>Daftar Tamu</h3>
            <p class="text-muted mb-0">Seluruh tamu yang telah mendaftar</p>
        </div>
        <a href="{{ route('tamu.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Isi Buku Tamu
        </a>
    </div>

    <!-- TABEL -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0">
            <form class="d-flex gap-2" action="{{ route('tamu.daftar') }}" method="GET">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari nama atau nomor HP..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 18%;">Alamat</th>
                            <th style="width: 12%;">No. HP</th>
                            <th style="width: 15%;">Keperluan</th>
                            <th style="width: 7%;">Paraf</th>
                            <th style="width: 8%;">Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamu as $i => $t)
                        <tr @if($loop->first) class="table-light" @endif>
                            <td class="text-center"><strong>{{ $tamu->firstItem() + $i }}</strong></td>
                            <td class="text-center">{{ $t->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td><strong>{{ $t->nama }}</strong></td>
                            <td>{{ $t->alamat }}</td>
                            <td class="text-center">{{ $t->no_hp }}</td>
                            <td>{{ Str::limit($t->keperluan_kunjungan, 40) }}</td>
                            <td class="text-center">
                                @if($t->paraf)
                                    <img src="{{ asset('storage/'.$t->paraf) }}" class="rounded shadow-sm" style="width: 60px; height: 40px; object-fit: cover;" alt="Paraf">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($t->foto)
                                    <img src="{{ asset('storage/'.$t->foto) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;" alt="Foto Tamu">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2"></i><br>Belum ada tamu yang mendaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tamu->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $tamu->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
