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
            background: linear-gradient(135deg, #115e59 0%, #0f766e 50%, #0369a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
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
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 1.5rem;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.35);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }

        .brand-badge {
            width: 56px;
            height: 56px;
            border-radius: 1.25rem;
            background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(15, 118, 110, 0.3);
        }

        .form-control:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 0.25rem rgba(15, 118, 110, 0.15);
        }

        .demo-pill {
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.8rem;
        }

        .demo-pill:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="login-card p-4 p-sm-5">
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="text-decoration-none">
                <div class="brand-badge mb-3">
                    <i class="bi bi-mortarboard-fill fs-2"></i>
                </div>
                <h3 class="fw-bold mb-1" style="font-family: 'Outfit'; color:#0f172a;">SekolahKu SaaS</h3>
            </a>
            <p class="text-muted small">SIM Akademik & Digital Finance Sekolah</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 small py-2.5 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('success_pending'))
            <div class="alert alert-warning rounded-3 small py-3 mb-3 border-warning">
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
            <div class="alert alert-danger rounded-3 small py-2 mb-3">
                <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i
                            class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" id="email" class="form-control bg-light border-start-0"
                        placeholder="nama@sekolah.sch.id" required value="{{ old('email') }}">
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label small fw-semibold text-dark">Kata Sandi</label>
                </div>
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

            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-semibold shadow-sm mb-3">
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
</body>

</html>