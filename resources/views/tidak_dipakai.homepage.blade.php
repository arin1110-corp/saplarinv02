<!DOCTYPE html>
<html lang="en">

<head>
    <title>SAPLARIN - Sistem Admininstrasi Pelaporan Internal</title>
    @include('partials.header')
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- Fullscreen Tengah -->
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="container text-center">

            <h4 class="mb-3"><img src="{{asset('image/pemprov.png')}}"></h4>
            <h1 class="display-1 fw-bold text-secondary">
                SAPLAR<span class="text-danger">IN</span>
            </h1>
            <p class="display-7 fw-bold text-secondary">
                Sistem Admininstrasi Pengelolaan Laporan Internal
            </p>
            <p class="display-7 fw-bold text-secondary">
                Dinas Kebudayaan Provinsi Bali
            </p>

            <div class="container mt-5">
                <div class="d-flex flex-column gap-4 align-items-center">

                    <!-- MENU 1 -->
                    <a href="{{ url('/login-user') }}"
                        class="menu-row animate-fade delay-0 text-decoration-none text-dark">
                        <div class="d-flex align-items-center">

                            <!-- KIRI: Icon + Judul -->
                            <div class="menu-left text-center px-3">
                                <i class="bi bi-pencil-square icon-top"></i>
                                <h6 class="mt-2 fw-bold">INPUT LAPORAN</h6>
                            </div>
                            &nbsp&nbsp&nbsp
                            <!-- GARIS PEMBATAS -->
                            <div class="divider-vert"></div>

                            <!-- KANAN: Deskripsi -->
                            <div class="menu-right ps-3">
                                <p class="text-muted mb-0 big">Unggah dan Kelola Laporan Kegiatan dengan Cepat dan
                                    Aman.</p>
                            </div>

                        </div>
                    </a>

                    <!-- MENU 2 -->
                    <a href="{{ url('/login-verifikator') }}"
                        class="menu-row animate-fade delay-1 text-decoration-none text-dark">
                        <div class="d-flex align-items-center">

                            <div class="menu-left text-center px-3">
                                <i class="bi bi-person-check-fill icon-top"></i>
                                <h6 class="mt-2 fw-bold">VERIFIKATOR </h6>
                            </div>
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            <div class="divider-vert"></div>

                            <div class="menu-right ps-3">
                                <p class="text-muted mb-0 big">Verifikator Melakukan Verifikasi Inputan yang
                                    Sudah dilakukan.</p>
                            </div>

                        </div>
                    </a>
                    <!-- MENU 3 -->
                    <a href="{{ url('/login-admin') }}"
                        class="menu-row animate-fade delay-1 text-decoration-none text-dark">
                        <div class="d-flex align-items-center">

                            <div class="menu-left text-center px-3">
                                <i class="bi bi-person-bounding-box icon-top"></i>
                                <h6 class="mt-2 fw-bold">ADMINISTRATOR</h6>
                            </div>
                            &nbsp
                            <div class="divider-vert"></div>

                            <div class="menu-right ps-3">
                                <p class="text-muted mb-0 big">Administrator melakukan pengelolaan Data.</p>
                            </div>

                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <footer class="text-center py-4 px-3 bg-light small text-muted">
        &copy; {{ date('Y') }} Dinas Kebudayaan Provinsi Bali — <strong>SAPLARIN</strong>. All rights reserved.
        <span class="text-danger">|</span>
        <span class="text-dark">Crafted by <strong>ARIN</strong></span>
        <span class="text-muted">with Pranata Komputer Ahli Pertama <i class="bi bi-heart-fill text-danger"></i></span>
    </footer>
    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>