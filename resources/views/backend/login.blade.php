<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-icon.svg') }}">
    <title>Backend Login | SAKINAH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%); min-height: 100vh; }
        .login-card { max-width: 420px; border-radius: 1rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card login-card shadow-lg w-100">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo/logo.svg') }}" alt="Logo" class="mb-3" height="36">
                <h5 class="fw-bold text-dark">Backend Panel</h5>
                <p class="text-muted small mb-0">Sistem Aplikasi Kinerja Akuntabilitas Nasional</p>
            </div>

            <form action="{{ url('/backend') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-medium">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                @if(session('error'))
                <div class="alert alert-danger small py-2 mb-3"><i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger small py-2 mb-3">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                @endif

                <button type="submit" class="btn w-100 text-white fw-medium" style="background:#0f766e;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </form>

            <p class="text-center text-muted small mt-3 mb-0">Hanya Superadmin yang dapat mengakses panel ini</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
