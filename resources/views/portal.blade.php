<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            padding: 20px;
        }
        .portal-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
        }
        .header {
            background: #2a5298;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
        }
        .barcode-container {
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            flex: 1;
        }
        .qr-img {
            max-width: 280px;
            border: 12px solid white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin: 0 auto;
            display: block;
        }
        .instructions {
            padding: 30px;
            background: white;
            flex: 1;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 12px 0;
            font-size: 15px;
        }
        .step-number {
            background: #2a5298;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2a5298;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            cursor: pointer;
            z-index: 1000;
        }
        
        /* Desktop Layout - 2 Kolom */
        @media (min-width: 768px) {
            .content-wrapper {
                flex-direction: row;
            }
            .barcode-container, .instructions {
                min-height: 500px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .step {
                font-size: 16px;
            }
            .step-number {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }
        }
        
        @media print {
            .print-btn { display: none; }
            body { background: white; padding: 0; }
            .portal-card { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="portal-card">
        <div class="header">
            <h1 class="h3 mb-0">📖 Buku Tamu Digital</h1>
            <p class="mb-0 mt-2 opacity-75">Diskominfo Barito Selatan</p>
        </div>
        
        <div class="content-wrapper">
            <div class="barcode-container">
                <img src="{{ asset('images/qr-diskominfo.png') }}" 
                    alt="QR Code Buku Tamu" 
                    class="img-fluid qr-img">

                <p class="mt-4 fw-bold text-primary h5">
                    SCAN QR CODE INI
                </p>
                <p class="text-muted small">
                    atau buka: <a href="{{ url('/isi') }}" class="text-decoration-underline text-primary fw-semibold">{{ url('/isi') }}</a>
                </p>
            </div>
            
            <div class="instructions">
                <h4 class="text-center mb-4 text-primary fw-bold">
                    <i class="fas fa-list-ol me-2"></i>Petunjuk Penggunaan
                </h4>
                
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Buka kamera HP atau app QR Scanner</div>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Arahkan ke QR Code di sebelah kiri</div>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Isi Form: Nama, Alamat, No. HP, Keperluan</div>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Isi Tanda Tangan / Paraf (Opsional)</div>
                </div>
                
                <div class="step">
                    <div class="step-number">5</div>
                    <div>Klik "Kirim" → Selesai!</div>
                </div>
            </div>
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">
        🖨️ CETAK
    </button>

</body>
</html>
