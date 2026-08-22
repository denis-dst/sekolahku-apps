<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SekolahKu SaaS Platform</title>
    <!-- Preconnect & DNS-Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Non-blocking Google Fonts with display=swap -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    </noscript>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Non-blocking Bootstrap Icons -->
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </noscript>

    <style>
        @font-face {
            font-family: 'bootstrap-icons';
            font-display: swap;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: #0f172a;
        }

        .btn-primary {
            background-color: #0f766e !important;
            border-color: #0f766e !important;
            color: #ffffff !important;
        }

        .btn-primary:hover {
            background-color: #115e59 !important;
            border-color: #115e59 !important;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        .brand-badge {
            width: 54px;
            height: 54px;
            border-radius: 1rem;
            background: #0f766e;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            min-height: 44px;
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.15);
        }

        .input-group-text {
            min-height: 44px;
            border-radius: 0.5rem;
        }

        @media (max-width: 575.98px) {
            .form-control, .form-select {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body>
    <main class="d-flex align-items-center justify-content-center w-100">
        <div class="login-card p-4 p-sm-5">
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <div class="brand-badge mb-2.5">
                        <i class="bi bi-mortarboard-fill fs-2"></i>
                    </div>
                    <h1 class="fw-bold fs-3 mb-1" style="font-family: 'Outfit'; color:#0f172a;">SekolahKu SaaS</h1>
                </a>
                <p class="text-muted small m-0">SIM Akademik & Digital Finance Sekolah</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3 small py-2.5 mb-3 d-flex align-items-center gap-2 border border-success-subtle bg-success-subtle text-success">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('success_pending'))
                <div class="alert alert-warning rounded-3 small py-3 mb-3 border-warning-subtle bg-warning-subtle text-dark">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-hourglass-split fs-5 text-warning"></i>
                        <div>
                            <strong class="d-block mb-1 text-dark">Pendaftaran Berhasil!</strong>
                            <span class="text-muted">{{ session('success_pending') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-3 small py-2.5 mb-3 border border-danger-subtle bg-danger-subtle text-danger">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary" for="email">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" id="email" class="form-control bg-light border-start-0"
                            placeholder="nama@sekolah.sch.id" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary" for="password">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" id="password" class="form-control bg-light border-start-0"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-xs mb-3" style="min-height: 44px;">
                    Masuk ke Sistem <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <div class="border-top pt-3 text-center">
                <span class="text-muted small">Belum mendaftarkan Yayasan / Sekolah Anda?</span>
                <a href="{{ route('register') }}" class="d-block fw-bold text-decoration-none mt-1" style="color: #0f766e;">
                    <i class="bi bi-building-add me-1"></i> Daftar Sekolah / Yayasan Baru
                </a>
            </div>
        </div>
    </main>
</body>

</html>