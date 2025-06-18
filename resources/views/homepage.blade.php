<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Beranda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-light">

    <!-- Fullscreen Tengah -->
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container text-center">

            <h4 class="mb-4"><img src="{{asset('image/pemprov.png')}}"></h4>
            <h1 class="mb-2">SAPLARIN</h1>

            <div class="menu-slider-wrapper">
                <div class="menu-slider">

                    <a href="{{ url('/kopi') }}">
                        <div class="menu-card">
                            <i class="bi bi-cup-hot-fill"></i>
                            <div>Kopi</div>
                        </div>
                    </a>

                    <a href="{{ url('/lokasi') }}">
                        <div class="menu-card">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>Lokasi</div>
                        </div>
                    </a>

                    <a href="{{ url('/kontak') }}">
                        <div class="menu-card">
                            <i class="bi bi-telephone-fill"></i>
                            <div>Kontak</div>
                        </div>
                    </a>

                    <a href="{{ url('/produk') }}">
                        <div class="menu-card">
                            <i class="bi bi-bag-fill"></i>
                            <div>Produk</div>
                        </div>
                    </a>

                    <a href="{{ url('/tentang') }}">
                        <div class="menu-card">
                            <i class="bi bi-people-fill"></i>
                            <div>Tentang</div>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>