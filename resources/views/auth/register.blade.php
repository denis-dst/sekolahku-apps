<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sekolah & Yayasan Baru — SekolahKu-Apps</title>
    <!-- Preconnect & DNS-Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Non-blocking Google Fonts with display=swap -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap">
    </noscript>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Non-blocking Bootstrap Icons -->
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </noscript>

    <style>
        @font-face {
            font-family: 'bootstrap-icons';
            font-display: swap;
        }
        :root {
            --primary: #0f766e;
            --primary-dark: #115e59;
            --primary-light: #f0fdfa;
            --accent: #22c55e;
            --dark: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            color: #334155;
            padding: 32px 16px;
        }

        .brand-logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .register-card {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .plan-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            background: #ffffff;
        }

        .plan-card:hover {
            border-color: #94a3b8;
        }

        .plan-card.active {
            border-color: var(--primary);
            background-color: var(--primary-light);
            box-shadow: 0 0 0 1px var(--primary);
        }

        .plan-radio {
            position: absolute;
            top: 14px;
            right: 14px;
        }

        .popular-badge {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            background: #0f766e;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
            min-height: 42px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
        }

        .section-divider {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary);
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 6px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 575.98px) {
            .form-control, .form-select {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body>
    <main id="main-content" class="container" style="max-width: 920px;">
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="brand-logo mb-2">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <span>Sekolah<span style="color: var(--primary);">Ku</span>-Apps</span>
            </a>
            <h1 class="fw-bold fs-3 text-dark mt-2 mb-1" style="font-family: 'Outfit';">Daftar Sekolah & Yayasan Baru</h1>
            <p class="text-muted small m-0">Mulai digitalisasi sistem sekolah, presensi, e-rapor, dan keuangan BOSP Anda.</p>
        </div>

        <div class="register-card p-3 p-sm-4 p-md-5">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 mb-4 border border-danger-subtle bg-danger-subtle text-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="registerForm">
                @csrf

                <!-- STEP 1: PILIH PAKET BERLANGGANAN -->
                <div class="section-divider">
                    <i class="bi bi-tag-fill"></i> 1. Pilih Paket Lisensi Layanan
                </div>

                <div class="row g-3 mb-4 align-items-stretch">
                    @foreach($plans as $plan)
                        @php
                            $isDefault = (old('subscription_plan_id') == $plan->id) || (!old('subscription_plan_id') && strtolower($plan->code) == strtolower($selectedPlanCode));
                            $isPopular = in_array(strtolower($plan->code), ['pro', 'professional']);
                        @endphp
                        <div class="col-12 col-md-4 d-flex">
                            <div class="plan-card w-100 h-100 d-flex flex-column justify-content-between {{ $isDefault ? 'active' : '' }}" onclick="selectPlan({{ $plan->id }}, this)">
                                <div>
                                    <input type="radio" name="subscription_plan_id" value="{{ $plan->id }}" class="form-check-input plan-radio" {{ $isDefault ? 'checked' : '' }} required>
                                    
                                    @if($isPopular)
                                        <span class="popular-badge">Paling Populer</span>
                                    @endif
                                    
                                    <h6 class="fw-bold m-0 text-dark">{{ $plan->name }}</h6>
                                    <div class="mt-2 mb-2">
                                        @if($plan->price == 0)
                                            <span class="fs-4 fw-bold text-success">Gratis</span>
                                            <small class="text-muted">/ 1 Bulan</small>
                                        @else
                                            <span class="fs-4 fw-bold text-primary">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                            <small class="text-muted">/ bln</small>
                                        @endif
                                    </div>
                                    
                                    <p class="text-muted small mb-2" style="font-size: 0.78rem;">{{ $plan->description }}</p>
                                </div>
                                
                                <div class="small fw-semibold text-dark border-top pt-2 mt-auto" style="font-size: 0.76rem;">
                                    <div><i class="bi bi-check2 text-success me-1"></i> {{ $plan->max_schools > 1 ? 'Multi-Sekolah (' . $plan->max_schools . ' Unit)' : '1 Unit Sekolah' }}</div>
                                    <div><i class="bi bi-check2 text-success me-1"></i> {{ $plan->max_siswas == 0 ? 'Unlimited Siswa' : 'Maks ' . $plan->max_siswas . ' Siswa' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- STEP 2: DATA YAYASAN & UNIT SEKOLAH -->
                <div class="section-divider mt-4">
                    <i class="bi bi-building"></i> 2. Informasi Yayasan / Lembaga & Sekolah
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Nama Yayasan / Badan Pengelola <span class="text-danger">*</span></label>
                        <input type="text" name="yayasan_name" class="form-control bg-light" placeholder="Contoh: Yayasan Bina Insan Nusantara" value="{{ old('yayasan_name') }}" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Nama yayasan atau organisasi induk yang menaungi sekolah.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Nama Unit Sekolah Pertama <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" class="form-control bg-light" placeholder="Contoh: TK Islam Terpadu Al-Falah" value="{{ old('school_name') }}" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Nama unit sekolah utama yang akan didaftarkan.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Jenjang Pendidikan Sekolah <span class="text-danger">*</span></label>
                        <select name="jenjang" class="form-select bg-light" required>
                            <option value="PAUD/TK/RA" {{ old('jenjang') == 'PAUD/TK/RA' ? 'selected' : '' }}>PAUD / TK / RA</option>
                            <option value="SD/MI" {{ old('jenjang') == 'SD/MI' ? 'selected' : '' }}>SD / MI</option>
                            <option value="SMP/MTs" {{ old('jenjang') == 'SMP/MTs' ? 'selected' : '' }}>SMP / MTs</option>
                            <option value="SMA/SMK/MA" {{ old('jenjang') == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA / SMK / MA</option>
                            <option value="Pesantren" {{ old('jenjang') == 'Pesantren' ? 'selected' : '' }}>Pondok Pesantren</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">No. WhatsApp / Telepon Lembaga <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control bg-light" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <!-- STEP 3: AKUN ADMINISTRATOR -->
                <div class="section-divider mt-4">
                    <i class="bi bi-person-badge-fill"></i> 3. Akun Administrator Utama
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Nama Lengkap Administrator <span class="text-danger">*</span></label>
                        <input type="text" name="admin_name" class="form-control bg-light" placeholder="Contoh: Drs. H. Ahmad Fauzi, M.Pd." value="{{ old('admin_name') }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Email Login <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control bg-light" placeholder="admin@yayasanalfalah.sch.id" value="{{ old('email') }}" required>
                        <small class="text-muted" style="font-size: 0.75rem;">Digunakan untuk masuk ke panel sistem.</small>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Kata Sandi (Password) <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control bg-light" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold text-secondary small">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <div class="p-3 bg-light-subtle rounded-3 border mb-4">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-info-circle-fill text-primary fs-5 mt-0.5"></i>
                        <div class="small text-muted">
                            Untuk paket <strong>Pro & Enterprise</strong>, setelah Anda mendaftar akun akan diteruskan ke tim Superadmin untuk aktivasi lisensi & verifikasi. Anda akan menerima notifikasi status setelah disetujui.
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary py-3 rounded-3 fw-bold fs-6 shadow-xs" style="min-height: 48px;">
                        <i class="bi bi-check-circle me-1"></i> Daftarkan Sekolah / Yayasan Sekarang
                    </button>
                </div>

                <div class="text-center">
                    <span class="text-muted small">Sudah memiliki akun terdaftar?</span>
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--primary);">Masuk di Sini</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        function selectPlan(planId, cardElement) {
            document.querySelectorAll('.plan-card').forEach(el => el.classList.remove('active'));
            cardElement.classList.add('active');
            cardElement.querySelector('input[type="radio"]').checked = true;
        }
    </script>
</body>

</html>
