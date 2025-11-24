<!-- resources/views/tamu/form.blade.php -->
<form method="POST" action="{{ route('tamu.store') }}" enctype="multipart/form-data" id="tamuFormDashboard">
        @csrf
    <input type="hidden" name="paraf_data" id="parafDataDashboard">

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nama <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Alamat <span class="text-danger">*</span></label>
            <input type="text" name="alamat" class="form-control" required value="{{ old('alamat') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">No. HP <span class="text-danger">*</span></label>
            <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Keperluan Kunjungan <span class="text-danger">*</span></label>
            <textarea name="keperluan_kunjungan" class="form-control" rows="3" required>{{ old('keperluan_kunjungan') }}</textarea>
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
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg px-5">Kirim Data</button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signaturePad');
        const ctx = canvas.getContext('2d');
        const hiddenInput = document.getElementById('parafData');
        let drawing = false;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function start(e) { drawing = true; draw(e); }
        function draw(e) {
            if (!drawing) return;
            e.preventDefault();
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000';
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || e.touches[0].clientX) - rect.left;
            const y = (e.clientY || e.touches[0].clientY) - rect.top;
            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
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

        document.getElementById('clearSignature').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hiddenInput.value = '';
        });

        // KIRIM SEBAGAI FILE LANGSUNG → TIDAK DOBEL!
        document.getElementById('tamuForm').addEventListener('submit', function(e) {
            if (hiddenInput.value && !document.querySelector('[name="paraf_file"]').files.length) {
                canvas.toBlob(function(blob) {
                    const file = new File([blob], "paraf.png", { type: "image/png" });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    document.querySelector('[name="paraf_file"]').files = dt.files;
                    this.submit();
                }, 'image/png');
                e.preventDefault();
            }
        });
    });
</script>
@endpush