<!DOCTYPE html>
<html lang="en">

<head>
    <title>SAPLARIN - Login User</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS (jika ada) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('image/pemprov.png') }}" height="60" alt="Logo">
            <h1 class="mt-3 fw-bold text-secondary">LOGIN USER</h1>
            <h1 class="display-7 fw-bold text-secondary">
                SAPLAR<span class="text-danger">IN</span>
            </h1>
            <p class="text-muted small">Sistem Administrasi Pelaporan Internal</p>
        </div>

        <form method="POST" action="{{ route('login.user') }}" class="form-login">
            @csrf

            <!-- NIP / Email -->
            <div class="mb-3">
                <label class="form-label">NIP / Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required autofocus>
                </div>
                @error('nip')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>
                @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-danger">Login</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                Belum punya akun?
                <a href="{{ url('/daftarakunuser') }}" style="color: #c0392b;">Daftar Disini.</a>
            </small>
        </div>
    </div>

    <!-- JS: Bootstrap & Toggle Password -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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