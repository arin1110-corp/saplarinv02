<!DOCTYPE html>
<html lang="en">

<head>
    <title>SAPLARIN - Login Admin</title>
    @include('partials.header')
</head>

<body class="login-admin">

    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-person-badge-fill display-4 text-light"></i>
            <h2 class="mt-3 fw-bold text-light">LOGIN ADMIN</h2>
            <h1 class="display-6 fw-bold text-light">
                SAPLAR<span class="text-danger">IN</span>
            </h1>
            <p class="text-muted small">Panel Kendali Admin Sistem Pelaporan</p>

            @error('admin_username')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <form method="POST" action="{{ route('login.admin') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="admin_username" class="form-control" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="admin_password" id="password" class="form-control" required>
                    <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-navy">Login</button>
            </div>
        </form>

    </div>

    <!-- Script -->
    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        toggle.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>