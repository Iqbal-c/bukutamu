<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Portal Buku Tamu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .portal-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 500px;
        }
        .header {
            background: #2a5298;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .barcode-container {
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
        }
        #qrcode {
            width: 250px;
            height: 250px;
            margin: 0 auto;
            border: 10px solid white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .instructions {
            padding: 30px;
            background: white;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 15px 0;
            font-size: 16px;
        }
        .step-number {
            background: #2a5298;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2a5298;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-size: 18px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 1000;
        }
        @media print {
            .print-btn { display: none; }
            body { background: white; }
        }
    </style>
</head>
<body>
    <div class="portal-card">
        <div class="header">
            <h1>📖 Buku Tamu Digital</h1>
            <p class="mb-0">Diskominfo Barito Selatan</p>
        </div>

        
        <!-- GANTI BAGIAN barcode-container DENGAN INI -->
        <div class="barcode-container">
            <!-- GAMBAR LOGO / QR CODE CUSTOM -->
            <img src="{{ asset('images/qr-diskominfo.png') }}" 
                alt="QR Code Buku Tamu" 
                class="img-fluid" 
                style="max-width: 280px; border: 12px solid white; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">

            <p class="mt-3 fw-bold text-primary">
                SCAN QR CODE INI
            </p>
            <p class="text-muted small">
                atau buka: <a href="{{ url('/isi') }}" class="text-decoration-underline">{{ url('/isi') }}</a>
            </p>
        </div>
        
        <div class="instructions">
            <h4 class="text-center mb-4">Petunjuk Penggunaan</h4>
            
            <div class="step">
                <div class="step-number">1</div>
                <div>Buka kamera HP atau app QR Scanner</div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div>Arahkan ke barcode di atas</div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div>Isi form: nama, alamat, HP, pesan</div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div>Tanda tangan dengan jari</div>
            </div>
            
            <div class="step">
                <div class="step-number">5</div>
                <div>Klik "Kirim" → Selesai!</div>
            </div>
            
            <hr>
            
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">
        🖨️ CETAK
    </button>

    <script>
        QRCode.toCanvas(document.getElementById('qrcode'), '{{ url('/') }}', {
            width: 250,
            margin: 2,
            color: {
                dark: '#2a5298',
                light: '#ffffff'
            }
        });
    </script>
</body>
</html>