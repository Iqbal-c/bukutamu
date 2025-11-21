<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .signature-pad {
            border: 2px dashed #ccc;
            border-radius: 8px;
            width: 100%;
            height: 150px;
            background-color: #f9f9f9;
            touch-action: none;
        }
        .signature-buttons {
            margin-top: 5px;
        }
        .paraf-img, .foto-img {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        @media (max-width: 768px) {
            .signature-pad { height: 120px; }
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <!-- QR Code -->
    <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ url('/') }}" 
             alt="Scan QR Code" class="img-fluid">
        <p class="text-muted mt-2"><strong>Scan dengan HP untuk isi buku tamu</strong></p>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Isi Buku Tamu</h4>
        </div>
        <div class="card-body">
            <form id="tamuForm" method="POST" action="/tamu" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="paraf_data" id="parafData">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Pesan dan Kesan <span class="text-danger">*</span></label>
                        <textarea name="pesan_kesan" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Tanda Tangan Digital -->
                    <div class="col-12">
                        <label class="form-label">Paraf / Tanda Tangan</label>
                        <canvas id="signaturePad" class="signature-pad"></canvas>
                        <div class="signature-buttons">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSignature">Hapus</button>
                            <small class="text-muted ms-2">Gambar tanda tangan dengan jari atau mouse</small>
                        </div>
                    </div>

                    <!-- Upload Foto Paraf (Alternatif) -->
                    <div class="col-md-6">
                        <label class="form-label">Atau Upload Foto Paraf (dari kertas)</label>
                        <input type="file" name="paraf_file" class="form-control" accept="image/*">
                    </div>

                    <!-- Upload Foto Tamu -->
                    <div class="col-md-6">
                        <label class="form-label">Foto Tamu (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>

                    

                    <!-- Submit -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5">Kirim Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Tamu -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tamu</h5>
            <input type="text" class="form-control form-control-sm w-50" placeholder="Cari nama / HP..."
                   value="{{ request('search') }}" 
                   onkeyup="if(event.key==='Enter') location.href='/?search='+this.value">
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Hari/Tanggal</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Pesan & Kesan</th>
                            <th>Paraf</th>
                            <th>Foto</th>
                            @auth
                            <th>Aksi</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tamu as $i => $t)
                        <tr>
                            <td>{{ $tamu->firstItem() + $i }}</td>
                            <td>{{ $t->created_at->translatedFormat('l, d M Y H:i') }}</td>
                            <td><strong>{{ $t->nama }}</strong></td>
                            <td>{{ $t->alamat }}</td>
                            <td>{{ $t->no_hp }}</td>
                            <td>{{ Str::limit($t->pesan_kesan, 40) }}</td>
                            <td>
                                @if($t->paraf)
                                    <img src="{{ asset('storage/'.$t->paraf) }}" class="paraf-img" alt="Paraf">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($t->foto)
                                    <img src="{{ asset('storage/'.$t->foto) }}" class="foto-img" alt="Foto">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @auth
                            <td>
                                <form action="{{ route('tamu.destroy', $t->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
                            @endauth
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted">Belum ada tamu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light">
                {{ $tamu->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Tanda Tangan -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signaturePad');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        // Set canvas size
        function resizeCanvas() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Start drawing
        function startDrawing(e) {
            drawing = true;
            draw(e);
        }

        // Draw
        function draw(e) {
            if (!drawing) return;
            ctx.lineWidth = 2;
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

        // Stop drawing
        function stopDrawing() {
            if (drawing) {
                drawing = false;
                ctx.beginPath();
                // Simpan ke hidden input
                document.getElementById('parafData').value = canvas.toDataURL();
            }
        }

        // Event listeners
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);

        // Clear button
        document.getElementById('clearSignature').addEventListener('click', function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('parafData').value = '';
        });

        // Form submit: jika ada tanda tangan, upload sebagai file
        document.getElementById('tamuForm').addEventListener('submit', function(e) {
            const parafData = document.getElementById('parafData').value;
            if (parafData && !document.querySelector('[name="paraf_file"]').files.length) {
                // Buat file dari canvas
                fetch(parafData)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], "paraf.png", { type: "image/png" });
                        const dtl = new DataTransfer();
                        dtl.items.add(file);
                        document.querySelector('[name="paraf_file"]').files = dtl.files;
                        this.submit();
                    });
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>