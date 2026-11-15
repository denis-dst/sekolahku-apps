<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Ingetin Mobile & API Hub</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-primary: #059669;
            --brand-dark: #064e3b;
            --brand-neon: #10b981;
            --brand-bg: #091312;
            --card-glass: rgba(15, 23, 22, 0.88);
            --card-border: rgba(16, 185, 129, 0.22);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--brand-bg);
            background-image: 
                radial-gradient(circle at 15% 20%, rgba(5, 150, 105, 0.22) 0%, transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(16, 185, 129, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(6, 78, 59, 0.35) 0%, transparent 60%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f1f5f9;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient animated grid line */
        .ambient-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(16, 185, 129, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(16, 185, 129, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .login-card {
            background: var(--card-glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 1.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(16, 185, 129, 0.12);
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            padding: 2.5rem 2.25rem;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6), 0 0 40px rgba(16, 185, 129, 0.2);
        }

        .brand-logo-glow {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #10b981, #047857);
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4), inset 0 0 15px rgba(255, 255, 255, 0.25);
            margin: 0 auto 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1.brand-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, #ffffff, #a7f3d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .pin-input {
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(16, 185, 129, 0.3);
            border-radius: 0.85rem;
            color: #ffffff;
            font-size: 1.6rem;
            letter-spacing: 8px;
            text-align: center;
            padding: 0.75rem 1rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .pin-input:focus {
            background: rgba(15, 23, 42, 0.85);
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.25);
            color: #ffffff;
            outline: none;
        }

        .form-control-custom {
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(148, 163, 184, 0.2);
            border-radius: 0.75rem;
            color: #ffffff;
            padding: 0.65rem 0.95rem;
        }

        .form-control-custom:focus {
            background: rgba(15, 23, 42, 0.85);
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            color: #ffffff;
        }

        .btn-neon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.85rem 1.5rem;
            border-radius: 0.85rem;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            transition: all 0.25s ease;
        }

        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.5);
            color: #ffffff;
        }

        .nav-pills-custom .nav-link {
            color: #94a3b8;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .nav-pills-custom .nav-link.active {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .badge-system {
            background: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 2rem;
            padding: 0.35rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="ambient-grid"></div>

    <div class="login-card text-center">
        <!-- Brand Icon -->
        <div class="brand-logo-glow">
            <i class="bi bi-moon-stars-fill text-white"></i>
        </div>

        <span class="badge-system mb-2 d-inline-block">
            <i class="bi bi-shield-check me-1"></i> SEKOLAHKU-APPS INTEGRATION
        </span>

        <h1 class="brand-title">Ingetin Hub</h1>
        <p class="text-slate-400 small mb-4" style="color: #94a3b8;">
            Akses Panel Kontrol Backend & Layanan REST API Mobile
        </p>

        @if(session('error'))
            <div class="alert alert-danger py-2 px-3 text-start small mb-3 border-0 rounded-3" style="background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3);">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info py-2 px-3 text-start small mb-3 border-0 rounded-3" style="background: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3);">
                <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
            </div>
        @endif

        <!-- Tab Nav -->
        <ul class="nav nav-pills nav-pills-custom justify-content-center mb-4 p-1 rounded-3" style="background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(148, 163, 184, 0.15);" id="loginTab" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100 active" id="pin-tab" data-bs-toggle="pill" data-bs-target="#tab-pin" type="button" role="tab">
                    <i class="bi bi-key-fill me-1"></i> PIN Cepat
                </button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <button class="nav-link w-100" id="account-tab" data-bs-toggle="pill" data-bs-target="#tab-account" type="button" role="tab">
                    <i class="bi bi-person-badge-fill me-1"></i> Akun SekolahKu
                </button>
            </li>
        </ul>

        <div class="tab-content text-start" id="loginTabContent">
            <!-- TAB PIN -->
            <div class="tab-pane fade show active" id="tab-pin" role="tabpanel">
                <form action="{{ route('ingetin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-center">
                        <label class="form-label small text-slate-300 fw-semibold mb-2" style="color: #cbd5e1;">Masukkan PIN Admin Ingetin</label>
                        <input type="password" 
                               name="pin" 
                               class="form-control pin-input" 
                               maxlength="8" 
                               placeholder="••••••" 
                               autofocus 
                               required>
                        <div class="text-xs mt-2" style="color: #64748b; font-size: 0.75rem;">
                            Default PIN: <strong class="text-emerald-400" style="color: #34d399;">123456</strong> (dapat diubah di panel)
                        </div>
                    </div>

                    <button type="submit" class="btn btn-neon w-100 mt-3 d-flex align-items-center justify-content-center gap-2">
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
                        <label class="form-label small text-slate-300" style="color: #cbd5e1;">Email SekolahKu</label>
                        <input type="email" name="email" class="form-control form-control-custom" placeholder="admin@sekolahku.id" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-slate-300" style="color: #cbd5e1;">Password</label>
                        <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-neon w-100 mt-2 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right fs-5"></i>
                        <span>Login dengan SekolahKu</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top border-slate-800 text-center" style="border-color: rgba(148, 163, 184, 0.15) !important;">
            <a href="{{ url('/') }}" class="text-decoration-none text-xs d-inline-flex align-items-center gap-1" style="color: #64748b; font-size: 0.78rem;">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda SekolahKu
            </a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
