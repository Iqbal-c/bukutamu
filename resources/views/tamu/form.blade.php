<!-- resources/views/tamu/form.blade.php -->
<form method="POST" action="{{ $formAction ?? route('tamu.store') }}" enctype="multipart/form-data" id="tamuForm">
    @csrf
    <input type="hidden" name="paraf_data" id="parafData">

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama') }}">
            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Alamat <span class="text-danger">*</span></label>
            <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" required value="{{ old('alamat') }}">
            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" required value="{{ old('no_hp') }}">
            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label">Keperluan Kunjungan <span class="text-danger">*</span></label>
            <textarea name="keperluan_kunjungan" class="form-control @error('keperluan_kunjungan') is-invalid @enderror" rows="3" required>{{ old('keperluan_kunjungan') }}</textarea>
            @error('keperluan_kunjungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Tanda Tangan -->
        <div class="col-12">
            <label class="form-label">Paraf / Tanda Tangan</label>
            <canvas id="signaturePad" class="signature-pad"></canvas>
            <div class="signature-buttons">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSignature">Hapus</button>
                <small class="text-muted ms-2">Gambar dengan jari/mouse</small>
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Upload Foto Paraf (Alternatif)</label>
            <input type="file" name="paraf_file" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
            <label class="form-label">Foto Tamu (Opsional)</label>
            <div class="row g-2">
                <div class="col-12">
                    <input type="file" name="foto" class="form-control" accept="image/*" capture="environment">
                </div>
                <div class="col-12">
                    <small class="text-muted">Klik untuk ambil foto dari kamera atau pilih dari galeri</small>
                </div>
            </div>
        </div>

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg px-5">Kirim Data</button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function initSignaturePad() {
        const canvas = document.getElementById('signaturePad');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const hiddenInput = document.getElementById('parafData');
        let drawing = false;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const newWidth = canvas.offsetWidth * ratio;
            const newHeight = canvas.offsetHeight * ratio;

            // Hanya resize jika ukurannya benar-benar berubah (mencegah terhapus di HP saat scroll)
            if (canvas.width !== newWidth || canvas.height !== newHeight) {
                // Simpan konten lama jika ada
                const tempImage = canvas.toDataURL();
                
                canvas.width = newWidth;
                canvas.height = newHeight;
                ctx.scale(ratio, ratio);

                // Kembalikan konten lama setelah resize
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

        const form = document.getElementById('tamuForm');
        if (form) {
            let formSubmitted = false;
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

                    // 1. Ambil Signature Pad (Paraf)
                    if (hiddenInput.value && !document.querySelector('[name="paraf_file"]').files.length) {
                        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                        const file = new File([blob], "paraf.png", { type: "image/png" });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        document.querySelector('[name="paraf_file"]').files = dt.files;
                    }

                    // 2. Kompres Foto Tamu jika ada
                    const fotoInput = document.querySelector('[name="foto"]');
                    if (fotoInput.files.length > 0) {
                        const originalFile = fotoInput.files[0];
                        
                        // Hanya kompres jika ukurannya > 1MB
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
    }

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
                    
                    canvas.toBlob(blob => {
                        resolve(blob);
                    }, 'image/jpeg', quality);
                };
                img.onerror = error => reject(error);
            };
            reader.onerror = error => reject(error);
        });
    }

    document.addEventListener('DOMContentLoaded', initSignaturePad);

    // Initialize signature pad when modal is shown
    document.addEventListener('shown.bs.modal', function () {
        setTimeout(initSignaturePad, 100);
    });
</script>
@endpush