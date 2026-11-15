<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Ingetin Mobile & API Hub</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-primary: #059669;
            --brand-primary-hover: #047857;
            --brand-light: #ecfdf5;
            --brand-border: #a7f3d0;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.06) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            padding: 1.5rem;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
            width: 100%;
            max-width: 440px;
            padding: 2.75rem 2.25rem;
        }

        .brand-logo-mark {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25);
            margin: 0 auto 1.25rem;
        }

        h1.brand-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.75rem;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .badge-system {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 2rem;
            padding: 0.35rem 0.85rem;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .pin-input {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 0.85rem;
            color: #0f172a;
            font-size: 1.6rem;
            letter-spacing: 8px;
            text-align: center;
            padding: 0.75rem 1rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .pin-input:focus {
            background: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15);
            color: #0f172a;
            outline: none;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            color: #0f172a;
            padding: 0.7rem 0.95rem;
            font-size: 0.92rem;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            color: #0f172a;
        }

        .btn-brand {
            background-color: var(--brand-primary);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.85rem 1.5rem;
            border-radius: 0.85rem;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            transition: all 0.2s ease;
        }

        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(5, 150, 105, 0.35);
        }

        .nav-pills-clean {
            background: #f1f5f9;
            padding: 4px;
            border-radius: 0.85rem;
            gap: 4px;
        }

        .nav-pills-clean .nav-link {
            color: #64748b;
            border-radius: 0.65rem;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.55rem 1rem;
            transition: all 0.2s;
            border: none;
        }

        .nav-pills-clean .nav-link.active {
            background: #ffffff;
            color: var(--brand-primary);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>

    <div class="login-card text-center">
        <!-- Brand Icon -->
        <div class="brand-logo-mark">
            <i class="bi bi-moon-stars-fill"></i>
        </div>

        <span class="badge-system mb-2.5 d-inline-block">
            <i class="bi bi-shield-check me-1"></i> SEKOLAHKU APPS
        </span>

        <h1 class="brand-title">Ingetin Hub</h1>
        <p class="text-muted small mb-4">
            Panel Kontrol Backend & Layanan REST API Mobile
        </p>

        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 text-start small mb-3 border-0 rounded-3" style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca !important;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info py-2 px-3 text-start small mb-3 border-0 rounded-3" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe !important;">
                <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
            </div>
        @endif

        <!-- Tab Nav -->
        <ul class="nav nav-pills nav-pills-clean justify-content-center mb-4" id="loginTab" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 active" id="pin-tab" data-bs-toggle="pill" data-bs-target="#tab-pin" type="button" role="tab">
                    <i class="bi bi-key me-1"></i> PIN Cepat
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="account-tab" data-bs-toggle="pill" data-bs-target="#tab-account" type="button" role="tab">
                    <i class="bi bi-person-badge me-1"></i> Akun SekolahKu
                </button>
            </li>
        </ul>

        <div class="tab-content text-start" id="loginTabContent">
            <!-- TAB PIN -->
            <div class="tab-pane fade show active" id="tab-pin" role="tabpanel">
                <form action="{{ route('ingetin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-center">
                        <label class="form-label mb-2">Masukkan PIN Admin Ingetin</label>
                        <input type="password" 
                               name="pin" 
                               class="form-control pin-input" 
                               maxlength="8" 
                               placeholder="••••••" 
                               autofocus 
                               required>
                        <div class="text-muted mt-2" style="font-size: 0.78rem;">
                            Default PIN: <strong class="text-success">123456</strong> (dapat diubah di panel)
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand w-100 mt-3 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                        <span>Buka Panel Ingetin</span>
                    </button>
                </form>
            </div>

            <!-- TAB SEKOLAHKU ACCOUNT -->
            <div class="tab-pane fade" id="tab-account" role="tabpanel">
                <form action="{{ route('ingetin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email SekolahKu</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@sekolahku.id" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-brand w-100 mt-2 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                        <span>Login dengan SekolahKu</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top text-center">
            <a href="{{ url('/') }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1" style="font-size: 0.82rem;">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda SekolahKu
            </a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
