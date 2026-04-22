<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Buku Tamu Digital')</title>

    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS dari home.blade.php -->
    <style>
        .signature-pad {
            border: 2px dashed #ccc;
            border-radius: 8px;
            width: 100%;
            height: 150px;
            background-color: #f9f9f9;
            touch-action: none;
        }
        .signature-buttons { margin-top: 5px; }
        .paraf-img, .foto-img {
            max-width: 80px; max-height: 80px;
            object-fit: cover; border-radius: 8px;
        }
        .qr-code { text-align: center; margin: 20px 0; }
        @media (max-width: 768px) { .signature-pad { height: 120px; } }
    </style>

    @stack('styles')
</head>
<body class="bg-light">
    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>