@extends('layouts.app')
@section('title', 'Isi Buku Tamu')

@section('content')
<div class="container py-4">
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

    <!-- QR Code -->
    <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ url('/isi') }}" 
             alt="Scan QR Code" class="img-fluid">
        <p class="text-muted mt-2"><strong>Scan dengan HP untuk isi buku tamu</strong></p>
    </div>

    <!-- Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Isi Buku Tamu</h4>
        </div>
        <div class="card-body">
            @include('tamu.form', ['formAction' => route('tamu.store')])
        </div>
    </div>
</div>

<!-- LOADING SPINNER -->
<div id="loadingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-white" role="status" style="width: 4rem; height: 4rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Loading Spinner untuk form submit
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
                }
                const loadingModal = document.getElementById('loadingModal');
                loadingModal.style.display = 'flex';
            });
        });
    });
</script>
@endpush
@endsection
