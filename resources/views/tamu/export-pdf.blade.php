<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Tamu</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #0d6efd; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #0d6efd; color: white; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; text-align: right; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <h1>Laporan Buku Tamu Digital - {{ $title ?? 'Semua Data' }}</h1>
    <p class="text-right">Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 18%;">Nama</th>
                <th style="width: 20%;">Alamat</th>
                <th style="width: 12%;">No. HP</th>
                <th style="width: 25%;">Keperluan Kunjungan</th>
                <th style="width: 20%;">Waktu Kunjungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tamu as $i => $t)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $t->nama }}</td>
                <td>{{ $t->alamat }}</td>
                <td>{{ $t->no_hp }}</td>
                <td>{{ $t->keperluan_kunjungan }}</td>
                <td>{{ $t->created_at->translatedFormat('d F Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh Sistem Buku Tamu Digital</p>
    </div>
</body>
</html>
