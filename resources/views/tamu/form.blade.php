<!-- resources/views/tamu/form.blade.php -->
<form method="POST" action="{{ route('tamu.store') }}" enctype="multipart/form-data" id="tamuForm">
    @csrf
    <input type="hidden" name="paraf_data" id="parafData">

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
            <label class="form-label">Pesan dan Kesan <span class="text-danger">*</span></label>
            <textarea name="pesan_kesan" class="form-control" rows="3" required>{{ old('pesan_kesan') }}</textarea>
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
    // JS Canvas & Konversi ke File (sama seperti /isi)
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signaturePad');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        function resizeCanvas() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function startDrawing(e) { drawing = true; draw(e); }
        function draw(e) {
            if (!drawing) return;
            ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.strokeStyle = '#000';
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || e.touches[0].clientX) - rect.left;
            const y = (e.clientY || e.touches[0].clientY) - rect.top;
            ctx.lineTo(x, y); ctx.stroke(); ctx.beginPath(); ctx.moveTo(x, y);
        }
        function stopDrawing() {
            if (drawing) { drawing = false; ctx.beginPath();
                document.getElementById('parafData').value = canvas.toDataURL();
            }
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);

        document.getElementById('clearSignature').addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('parafData').value = '';
        });

        document.getElementById('tamuForm').addEventListener('submit', function(e) {
            const parafData = document.getElementById('parafData').value;
            const parafFileInput = document.querySelector('[name="paraf_file"]');
            if (parafData && !parafFileInput.files.length) {
                fetch(parafData).then(res => res.blob()).then(blob => {
                    const file = new File([blob], "paraf.png", { type: "image/png" });
                    const dtl = new DataTransfer(); dtl.items.add(file);
                    parafFileInput.files = dtl.files;
                    this.submit();
                });
                e.preventDefault();
            }
        });
    });
</script>
@endpush