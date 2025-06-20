<!DOCTYPE html>
<html lang="en">

<head>
    <title>SAPLARIN - Login Verifikator</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-verifikator-page">

    <div class="auth-split-container">
        <!-- Left Pane -->
        <div class="left-pane">
            <i class="bi bi-people-fill"></i>
            <h2 class="mt-3 fw-bold">VERIFIKATOR</h2>
            <p class="text-white-50">Akses khusus untuk tim verifikasi internal</p>
        </div>

        <!-- Right Pane -->
        <div class="right-pane">
            <div class="login-box">
                <div class="text-center mb-4">
                    <img src="{{ asset('image/pemprov.png') }}" height="60" alt="Logo">
                    <h4 class="mt-3 fw-bold text-secondary">LOGIN VERIFIKATOR</h4>
                    <h1 class="display-7 fw-bold text-secondary">
                        SAPLAR<span class="text-danger">IN</span>
                    </h1>
                    <p class="text-muted small">Sistem Pelaporan Internal</p>
                </div>

                <form method="POST" action="{{ route('login.verifikator') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">NIP / Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" name="nip" class="form-control" value="{{ old('username') }}" required
                                autofocus>
                        </div>
                        @error('nip')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

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

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>