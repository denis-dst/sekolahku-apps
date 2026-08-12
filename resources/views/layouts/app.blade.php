<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SekolahKu SaaS') - SIM & Asisten Digital Finance Sekolah</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --sk-primary: #16a34a;
            --sk-primary-hover: #15803d;
            --sk-primary-light: #dcfce7;
            --sk-secondary: #0d9488;
            --sk-dark: #0f172a;
            --sk-card-bg: #ffffff;
            --sk-bg: #f8fafc;
            --sk-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--sk-bg);
            color: #334155;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
        }

        .btn-primary {
            background-color: var(--sk-primary);
            border-color: var(--sk-primary);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--sk-primary-hover);
            border-color: var(--sk-primary-hover);
        }

        .btn-outline-primary {
            color: var(--sk-primary);
            border-color: var(--sk-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--sk-primary);
            border-color: var(--sk-primary);
            color: #ffffff;
        }

        .bg-primary {
            background-color: var(--sk-primary) !important;
        }

        .text-primary {
            color: var(--sk-primary) !important;
        }

        /* Sidebar Styling with Scrollable Overflow */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0d2818 0%, #05190e 100%);
            color: #f8fafc;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #22c55e #05190e;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #05190e;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #22c55e;
            border-radius: 3px;
        }

        .sidebar-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            padding-bottom: 2rem;
        }

        .sidebar .nav-link {
            color: #a7f3d0;
            padding: 0.7rem 1.1rem;
            font-weight: 500;
            border-radius: 0.6rem;
            margin: 0.2rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
            box-shadow: 0 4px 14px rgba(22, 197, 94, 0.35);
            font-weight: 700;
        }

        .main-content {
            margin-left: 260px;
            padding: 1.5rem 2rem;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid var(--sk-border);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-wrapper">
            <!-- Brand Header -->
            <div class="p-3 d-flex align-items-center gap-2 border-bottom border-success border-opacity-25 mb-2">
                <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center shadow-sm" style="width:40px; height:40px;">
                    <i class="bi bi-mortarboard-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="m-0 fw-bold text-white">SekolahKu</h5>
                    <small class="text-xs text-success-subtle" style="font-size:0.75rem;">SaaS SIM & Finance</small>
                </div>
            </div>

            <div class="px-3 py-2 text-uppercase text-xs fw-bold text-success opacity-75 mt-1" style="font-size:0.7rem; letter-spacing:1px; color:#86efac !important;">
                NAVIGASI UTAMA
            </div>

            <!-- Scrollable Nav Links -->
            <nav class="nav flex-column flex-grow-1">
                <a class="nav-link {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('self-presensi'))
                    <a class="nav-link {{ request()->is('presensi/mandiri') ? 'active' : '' }}" href="{{ route('presensi.mandiri') }}">
                        <i class="bi bi-qr-code-scan"></i> Absen Mandiri
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('manage-presensi'))
                    <a class="nav-link {{ request()->is('presensi') && !request()->is('presensi/mandiri') ? 'active' : '' }}" href="{{ route('presensi.index') }}">
                        <i class="bi bi-calendar-check-fill"></i> Presensi Kelas
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('manage-spp') || Auth::user()->can('upload-spp-bukti'))
                    <a class="nav-link {{ request()->is('spp*') && !request()->is('spp/verifikasi*') ? 'active' : '' }}" href="{{ route('spp.index') }}">
                        <i class="bi bi-wallet2"></i> Pembayaran SPP
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('verify-spp-bukti'))
                    <a class="nav-link {{ request()->is('spp/verifikasi*') ? 'active' : '' }}" href="{{ route('spp.verifikasi.queue') }}">
                        <i class="bi bi-check2-circle"></i> Verifikasi SPP
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('manage-expenses') || Auth::user()->can('approve-expenses'))
                    <a class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                        <i class="bi bi-cash-stack"></i> BendaharaKu / LPJ
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('manage-anekdot'))
                    <a class="nav-link {{ request()->is('anekdot*') ? 'active' : '' }}" href="{{ route('anekdot.index') }}">
                        <i class="bi bi-journal-bookmark-fill"></i> Catatan Anekdot
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('manage-erapor') || Auth::user()->can('manage-assessments'))
                    <a class="nav-link {{ request()->is('erapor*') ? 'active' : '' }}" href="{{ route('erapor.index') }}">
                        <i class="bi bi-file-earmark-pdf-fill"></i> E-Rapor Digital
                    </a>
                @endif

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->can('manage-master-data') || Auth::user()->can('manage-school'))
                    <div class="px-3 py-2 text-uppercase text-xs fw-bold opacity-75 mt-3" style="font-size:0.7rem; letter-spacing:1px; color:#86efac !important;">
                        MASTER & PROFIL
                    </div>

                    <a class="nav-link {{ request()->is('siswa*') ? 'active' : '' }}" href="{{ route('siswa.index') }}">
                        <i class="bi bi-people-fill"></i> Data Siswa
                    </a>
                    <a class="nav-link {{ request()->is('guru*') ? 'active' : '' }}" href="{{ route('guru.index') }}">
                        <i class="bi bi-person-badge-fill"></i> Data Guru
                    </a>
                    <a class="nav-link {{ request()->is('rombel*') ? 'active' : '' }}" href="{{ route('rombel.index') }}">
                        <i class="bi bi-building"></i> Rombel & Kelas
                    </a>
                    <a class="nav-link {{ request()->is('settings/school*') ? 'active' : '' }}" href="{{ route('settings.school.edit') }}">
                        <i class="bi bi-gear-wide-connected"></i> Profil & QRIS
                    </a>
                @endif

                @role('Superadmin')
                    <div class="px-3 py-2 text-uppercase text-xs fw-bold opacity-75 mt-3" style="font-size:0.7rem; letter-spacing:1px; color:#fbbf24 !important;">
                        SUPERADMIN SAAS
                    </div>
                    <a class="nav-link {{ request()->is('admin/plans*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">
                        <i class="bi bi-box-seam-fill"></i> Paket & Fitur (Plans)
                    </a>
                    <a class="nav-link {{ request()->is('admin/subscriptions*') ? 'active' : '' }}" href="{{ route('admin.subscriptions.index') }}">
                        <i class="bi bi-patch-check-fill"></i> Langganan Sekolah
                    </a>
                    <a class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-shield-lock-fill"></i> Role & Hak Akses (RBAC)
                    </a>
                @endrole
            </nav>

            <!-- User Footer Box -->
            <div class="p-3 border-top border-success border-opacity-25 bg-black bg-opacity-30 rounded-3 mx-2 mt-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:36px; height:36px;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden" style="max-width:120px;">
                            <div class="fw-semibold text-white text-truncate small">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-success-subtle text-truncate" style="font-size:0.72rem;">{{ Auth::user()->roles->first()?->name ?? 'User' }}</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-2" title="Keluar">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Body Content -->
    <main class="main-content">
        <!-- Top Navbar Header -->
        <header class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h4 class="fw-bold m-0 text-dark">@yield('page_title', 'Dashboard')</h4>
                    <p class="text-muted small m-0">{{ Auth::user()->school->name ?? 'SekolahKu Platform' }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @php
                    $tenant = Auth::user()->tenant ?? Auth::user()->school?->tenant;
                    $planName = $tenant?->subscriptionPlan?->name ?? 'Free Plan';
                    $planCode = $tenant?->subscriptionPlan?->code ?? 'free';
                    $badgeBg = match($planCode) {
                        'pro' => 'bg-success text-white',
                        'enterprise' => 'bg-primary text-white',
                        default => 'bg-secondary text-white'
                    };
                @endphp
                <span class="badge {{ $badgeBg }} px-3 py-2 rounded-pill font-monospace" style="font-size:0.8rem;">
                    <i class="bi bi-star-fill me-1"></i> {{ strtoupper($planName) }}
                </span>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                    <i class="bi bi-buildings me-1"></i> {{ Auth::user()->school->jenjang ?? 'TK/PAUD' }}
                </span>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
