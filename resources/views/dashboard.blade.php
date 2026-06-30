@extends('layouts.app')
@section('title', 'Dashboard Admin')

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

    <!-- NOTIFIKASI ERROR -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- HEADER DASHBOARD -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="mb-1 text-primary"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            <p class="text-muted mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahTamuModal">
                <i class="fas fa-plus me-2"></i>Tambah Tamu
            </button>
            
            <!-- BUTTON EXPORT DROPDOWN -->
            <div class="dropdown">
                <button class="btn btn-success shadow-sm dropdown-toggle" type="button" id="exportExcelDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.csv') }}">Semua Data</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header"><i class="fas fa-calendar-week me-2"></i>Per Minggu</h6></li>
                    <li>
                        <form class="px-3" action="{{ route('admin.csv') }}" method="GET">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Pilih Tanggal</label>
                                <input type="week" name="week" class="form-control form-control-sm" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success w-100">Export</button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header"><i class="fas fa-calendar-alt me-2"></i>Per Bulan</h6></li>
                    <li>
                        <form class="px-3" action="{{ route('admin.csv') }}" method="GET">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Pilih Bulan</label>
                                <input type="month" name="month" class="form-control form-control-sm" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success w-100">Export</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-danger shadow-sm dropdown-toggle" type="button" id="exportPdfDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.pdf') }}">Semua Data</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header"><i class="fas fa-calendar-week me-2"></i>Per Minggu</h6></li>
                    <li>
                        <form class="px-3" action="{{ route('admin.pdf') }}" method="GET">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Pilih Tanggal</label>
                                <input type="week" name="week" class="form-control form-control-sm" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-danger w-100">Export</button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header"><i class="fas fa-calendar-alt me-2"></i>Per Bulan</h6></li>
                    <li>
                        <form class="px-3" action="{{ route('admin.pdf') }}" method="GET">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Pilih Bulan</label>
                                <input type="month" name="month" class="form-control form-control-sm" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-danger w-100">Export</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- MODAL FORM -->
        <div class="modal fade" id="tambahTamuModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Tamu Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('tamu.form', ['formAction' => route('tamu.store'), 'showTanggalMasuk' => true])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIK CARD -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
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
            <div class="card border-0 shadow-sm bg-success text-white h-100">
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
            <div class="card border-0 shadow-sm bg-warning text-white h-100">
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
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-calendar-week fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-0">{{ $tamu->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count() }}</h5>
                        <small>Bulan Ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL DAFTAR TAMU LENGKAP -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom-0">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h5 class="mb-0 text-primary"><i class="fas fa-list me-2"></i>Daftar Tamu Lengkap</h5>
                <div class="d-flex flex-wrap gap-2">
                    <!-- Filter Sort -->
                    <form class="d-flex gap-2" id="sortForm" action="{{ route('dashboard') }}" method="GET">
                        <select name="sort_by" class="form-select" style="width: auto;" onchange="document.getElementById('sortForm').submit()">
                            <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Tanggal</option>
                            <option value="nama" {{ $sortBy === 'nama' ? 'selected' : '' }}>Nama</option>
                            <option value="alamat" {{ $sortBy === 'alamat' ? 'selected' : '' }}>Alamat</option>
                            <option value="no_hp" {{ $sortBy === 'no_hp' ? 'selected' : '' }}>No. HP</option>
                        </select>
                        <select name="sort_dir" class="form-select" style="width: auto;" onchange="document.getElementById('sortForm').submit()">
                            <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ $sortDir === 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>
                    <!-- Search -->
                    <form class="d-flex gap-2" action="{{ route('dashboard') }}" method="GET">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari nama / HP / alamat..." 
                               value="{{ request('search') }}" style="width: 250px;">
                        @if(request('sort_by'))
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <input type="hidden" name="sort_dir" value="{{ request('sort_dir') }}">
                        @endif
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 12%;">Tanggal</th>
                            <th style="width: 15%;">Nama</th>
                            <th style="width: 15%;">Alamat</th>
                            <th style="width: 10%;">No. HP</th>
                            <th style="width: 15%;">Keperluan</th>
                            <th style="width: 8%;">Paraf</th>
                            <th style="width: 8%;">Foto</th>
                            <th style="width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamu as $i => $t)
                        <tr @if($loop->first) class="table-light" @endif>
                            <td class="text-center"><strong>{{ $tamu->firstItem() + $i }}</strong></td>
                            <td class="text-center">{{ ($t->tanggal_masuk ?? $t->created_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td><strong>{{ $t->nama }}</strong></td>
                            <td>{{ Str::limit($t->alamat, 30) }}</td>
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
                                    <img src="{{ asset('storage/'.$t->foto) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;" alt="Foto">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.edit', $t->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.destroy', $t->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus" type="button"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2"></i><br>Belum ada tamu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tamu->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $tamu->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- LOADING SPINNER -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center py-4">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 mb-0 text-muted">Memproses data...</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Loading Spinner untuk form submit (KECUALI export Excel/PDF)
        const forms = document.querySelectorAll('form:not([action*="csv"]):not([action*="pdf"])');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                }
                const loadingModal = document.getElementById('loadingModal');
                loadingModal.style.display = 'flex';
            });
        });

        // Loading Spinner untuk Hapus
        document.querySelectorAll('.delete-form').forEach(function(form) {
            const btn = form.querySelector('button');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('⚠️ PERHATIAN!\nAnda yakin ingin menghapus data ini? Data yang dihapus tidak bisa dikembalikan!')) {
                    const loadingModal = document.getElementById('loadingModal');
                    loadingModal.style.display = 'flex';
                    form.submit();
                }
            });
        });

        // Grafik Harian
        @if(isset($labelsHari) && isset($dataHari))
        const ctxHarian = document.getElementById('chartHarian').getContext('2d');
        new Chart(ctxHarian, {
            type: 'bar',
            data: {
                labels: @json($labelsHari),
                datasets: [{
                    label: 'Jumlah Tamu',
                    data: @json($dataHari),
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        @endif

        // Grafik Bulanan
        @if(isset($labelsBulan) && isset($dataBulan))
        const ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
        new Chart(ctxBulanan, {
            type: 'line',
            data: {
                labels: @json($labelsBulan),
                datasets: [{
                    label: 'Jumlah Tamu',
                    data: @json($dataBulan),
                    fill: true,
                    backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
        @endif
    });
</script>
@endpush
@endsection
