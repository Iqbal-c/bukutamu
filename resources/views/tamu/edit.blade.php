@extends('layouts.app')
@section('title', 'Edit Tamu')

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

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Tamu: {{ $tamu->nama }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.update', $tamu->id) }}" method="POST" enctype="multipart/form-data" id="tamuForm">
                @csrf @method('PUT')
                <input type="hidden" name="paraf_data" id="parafData">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama', $tamu->nama) }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" required value="{{ old('alamat', $tamu->alamat) }}">
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" required value="{{ old('no_hp', $tamu->no_hp) }}">
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keperluan Kunjungan <span class="text-danger">*</span></label>
                        <textarea name="keperluan_kunjungan" class="form-control @error('keperluan_kunjungan') is-invalid @enderror" rows="3" required>{{ old('keperluan_kunjungan', $tamu->keperluan_kunjungan) }}</textarea>
                        @error('keperluan_kunjungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="col-12">
                        <label class="form-label">Paraf / Tanda Tangan</label>
                        @if($tamu->paraf)
                            <div class="mb-3">
                                <p class="small text-muted mb-1"><i class="fas fa-check-circle text-success"></i> Paraf saat ini:</p>
                                <img src="{{ asset('storage/'.$tamu->paraf) }}" class="img-thumbnail rounded shadow-sm" style="max-width: 200px;" alt="Paraf">
                            </div>
                        @endif
                        <canvas id="signaturePad" class="signature-pad"></canvas>
                        <div class="signature-buttons mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSignature">Hapus Paraf Baru</button>
                            <small class="text-muted ms-2">Gambar dengan jari/mouse untuk mengubah paraf (biarkan kosong untuk tidak mengubah)</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Upload Foto Paraf (Alternatif)</label>
                        <input type="file" name="paraf_file" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Tamu (Opsional)</label>
                        @if($tamu->foto)
                            <div class="mb-2">
                                <p class="small text-muted mb-1"><i class="fas fa-check-circle text-success"></i> Foto saat ini:</p>
                                <img src="{{ asset('storage/'.$tamu->foto) }}" class="img-thumbnail rounded shadow-sm" style="max-width: 150px" alt="Foto">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*" capture="environment">
                        <small class="text-muted d-block mt-1">Klik untuk ambil foto dari kamera atau pilih dari galeri</small>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i>Update Data</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>
                    </div>
                </div>
            </form>
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
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signaturePad');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const hiddenInput = document.getElementById('parafData');
        let drawing = false;
        let formSubmitted = false;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const newWidth = canvas.offsetWidth * ratio;
            const newHeight = canvas.offsetHeight * ratio;

            // Hanya resize jika ukurannya benar-benar berubah (mencegah terhapus di HP saat scroll)
            if (canvas.width !== newWidth || canvas.height !== newHeight) {
                const tempImage = canvas.toDataURL();
                
                canvas.width = newWidth;
                canvas.height = newHeight;
                ctx.scale(ratio, ratio);

                const img = new Image();
                img.src = tempImage;
                img.onload = () => ctx.drawImage(img, 0, 0, canvas.offsetWidth, canvas.offsetHeight);
            }
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function start(e) { 
            drawing = true; 
            e.preventDefault();
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000';
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || e.touches[0].clientX) - rect.left;
            const y = (e.clientY || e.touches[0].clientY) - rect.top;
            ctx.beginPath();
            ctx.moveTo(x, y);
            ctx.lineTo(x, y);
            ctx.stroke();
        }
        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || e.touches[0].clientX) - rect.left;
            const y = (e.clientY || e.touches[0].clientY) - rect.top;
            ctx.lineTo(x, y);
            ctx.stroke();
        }
        function stop() {
            if (drawing) {
                drawing = false;
                hiddenInput.value = canvas.toDataURL('image/png');
            }
        }

        canvas.addEventListener('mousedown', start);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stop);
        canvas.addEventListener('mouseout', stop);
        canvas.addEventListener('touchstart', start);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stop);

        const clearBtn = document.getElementById('clearSignature');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hiddenInput.value = '';
            });
        }

        // Loading Spinner untuk form submit
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...';
                }
                const loadingModal = document.getElementById('loadingModal');
                loadingModal.style.display = 'flex';
            });
        });

        // Fix: submit form with PUT method and prevent creating new data
        const form = document.getElementById('tamuForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                if (formSubmitted) return;
                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;

                try {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                    const loadingModal = document.getElementById('loadingModal');
                    if (loadingModal) loadingModal.style.display = 'flex';

                    // 1. Ambil Signature Pad (Paraf) jika ada coretan baru
                    if (hiddenInput.value && !document.querySelector('[name="paraf_file"]').files.length) {
                        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                        const file = new File([blob], "paraf.png", { type: "image/png" });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        document.querySelector('[name="paraf_file"]').files = dt.files;
                    }

                    // 2. Kompres Foto Tamu jika ada file baru
                    const fotoInput = document.querySelector('[name="foto"]');
                    if (fotoInput.files.length > 0) {
                        const originalFile = fotoInput.files[0];
                        if (originalFile.size > 1024 * 1024) {
                            const compressedBlob = await compressImage(originalFile, 0.7, 1200);
                            const compressedFile = new File([compressedBlob], originalFile.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });
                            const dt = new DataTransfer();
                            dt.items.add(compressedFile);
                            fotoInput.files = dt.files;
                        }
                    }

                    // 3. Ensure PUT method is sent
                    if (!form.querySelector('input[name="_method"]')) {
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        form.appendChild(methodInput);
                    }

                    formSubmitted = true;
                    form.submit();

                } catch (error) {
                    console.error('Gagal memproses form:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    if (loadingModal) loadingModal.style.display = 'none';
                    alert('Terjadi kesalahan saat memproses gambar. Silakan coba lagi.');
                }
            });
        }
    });

    // Fungsi Helper Kompres Gambar
    async function compressImage(file, quality, maxWidth) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = event => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    if (width > maxWidth) {
                        height = (maxWidth / width) * height;
                        width = maxWidth;
                    }
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob(blob => resolve(blob), 'image/jpeg', quality);
                };
                img.onerror = error => reject(error);
            };
            reader.onerror = error => reject(error);
        });
    }
</script>
@endpush
@endsection
