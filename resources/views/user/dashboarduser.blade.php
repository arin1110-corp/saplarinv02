@extends('partials.header')
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="sidebar" style="background:#c0392b;">
        <div>
            <center>
                <div class="brand"><i class="bi bi-shield-lock-fill"></i> Admin</div>
            </center>
            <ul class="nav flex-column">
                <li><a class="nav-link navuser active" href="#"><i class="bi bi-house"></i> Dashboard</a></li>
                <li><a class="nav-link navuser" href="#"><i class="bi bi-people"></i> Pengguna</a></li>
            </ul>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn-logout w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>

    <div class="page-content container">
        <h2>Selamat Datang, Admin</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
            <div class="col">
                <div class="card p-3">
                    <h5>Total User</h5>
                    <p class="fs-4">25</p>
                </div>
            </div>
            <div class="col">
                <div class="card p-3">
                    <h5>Laporan Masuk</h5>
                    <p class="fs-4">12</p>
                </div>
            </div>
            <div class="col">
                <div class="card p-3">
                    <h5>Laporan Verifikasi</h5>
                    <p class="fs-4">8</p>
                </div>
            </div>
        </div>
        <footer class="py-3 bg-light border-top small text-muted">&copy; {{ date('Y') }} SAPLARIN. Developed
            by <b>ARIN</b> x Pranata Komputer.</footer>
    </div>
</body>

</html>