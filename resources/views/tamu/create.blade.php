@extends('layouts.app')
@section('title', 'Isi Buku Tamu')

@section('content')
<div class="container py-4">

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
            @include('tamu.form')
        </div>
    </div>
</div>

@push('scripts')
<script>
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

        function startDrawing(e) {
            drawing = true;
            draw(e);
        }

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

        function stopDrawing() {
            if (drawing) {
                drawing = false;
                ctx.beginPath();
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
                fetch(parafData)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], "paraf.png", { type: "image/png" });
                        const dtl = new DataTransfer();
                        dtl.items.add(file);
                        parafFileInput.files = dtl.files;
                        this.submit();
                    });
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection