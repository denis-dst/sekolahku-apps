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
            --sk-primary: #0f766e;
            --sk-primary-hover: #115e59;
            --sk-primary-light: #f0fdfa;
            --sk-secondary: #0369a1;
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
            background-color: var(--sk-primary) !important;
            border-color: var(--sk-primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--sk-primary-hover) !important;
            border-color: var(--sk-primary-hover) !important;
        }

        .btn-outline-primary {
            color: var(--sk-primary) !important;
            border-color: var(--sk-primary) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--sk-primary) !important;
            border-color: var(--sk-primary) !important;
            color: #ffffff !important;
        }

        .bg-primary {
            background-color: var(--sk-primary) !important;
        }

        .text-primary {
            color: var(--sk-primary) !important;
        }

        .bg-primary-subtle {
            background-color: #ccfbf1 !important;
            color: #0f766e !important;
        }

        .bg-success-subtle {
            background-color: #d1fae5 !important;
            color: #047857 !important;
        }

        .bg-info-subtle {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }

        /* Sidebar Styling - Light & Clean (siakad-ridho) */
        .sidebar {
            width: 260px;
            background: #ffffff;
            color: #334155;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            border-right: 1px solid #e2e8f0;
            box-shadow: 2px 0 12px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #ffffff;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #ffffff;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100%;
            padding-bottom: 1.5rem;
        }

        .sidebar .nav-link {
            color: #475569;
            padding: 0.65rem 1rem;
            font-weight: 600;
            font-size: 0.825rem;
            border-radius: 0.75rem;
            margin: 0.15rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .sidebar .nav-link:hover {
            color: #0f172a;
            background: #f8fafc;
        }

        .sidebar .nav-link.active {
            color: #115e59;
            background: #f0fdfa;
            border-color: #ccfbf1;
            box-shadow: 0 2px 8px rgba(15, 118, 110, 0.08);
            font-weight: 700;
        }

        .sidebar-brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25);
        }

        .sidebar-section-title {
            padding: 0.75rem 1rem 0.35rem 1.1rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #0f766e;
        }

        .main-content {
            margin-left: 260px;
            padding: 1.75rem 2.25rem;
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .hero-banner {
            background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
            border: 1px solid #ccfbf1;
            border-radius: 1.25rem;
            padding: 1.75rem 2rem;
        }

        /* Custom Pagination Styling */
        .pagination {
            margin-bottom: 0;
            gap: 4px;
            display: flex;
            align-items: center;
        }

        .pagination .page-item .page-link {
            color: var(--sk-dark);
            border: 1px solid var(--sk-border);
            border-radius: 0.5rem;
            padding: 0.45rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 600;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            text-decoration: none;
        }

        .pagination .page-item .page-link:hover {
            background-color: #f0fdfa;
            color: #0f766e;
            border-color: #0f766e;
        }

        .pagination .page-item.active .page-link {
            background-color: #0f766e !important;
            border-color: #0f766e !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25);
        }

        .pagination .page-item.disabled .page-link {
            color: #94a3b8;
            background-color: #f8fafc;
            border-color: var(--sk-border);
            opacity: 0.7;
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
            <div class="p-3 d-flex align-items-center gap-3 border-bottom border-slate-100 mb-2">
                <div class="sidebar-brand-mark">
                    <i class="bi bi-mortarboard-fill fs-5"></i>
                </div>
                <div>
                    <h5 class="m-0 fw-bold" style="font-size:1.05rem; color:#0f172a;">SekolahKu</h5>
                    <small class="text-xs" style="font-size:0.72rem; color:#64748b;">SaaS SIM & Finance</small>
                </div>
            </div>

            <div class="sidebar-section-title">
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
                    <div class="sidebar-section-title mt-2">
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

                @if(Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('Yayasan Admin') || Auth::user()->can('manage-yayasan'))
                    <div class="sidebar-section-title mt-2" style="color:#6d28d9;">
                        YAYASAN & MULTI-UNIT
                    </div>
                    <a class="nav-link {{ request()->is('schools*') ? 'active' : '' }}" href="{{ route('schools.index') }}">
                        <i class="bi bi-diagram-3-fill"></i> Unit Sekolah Yayasan
                    </a>
                @endif

                @role('Superadmin')
                    <div class="sidebar-section-title mt-2" style="color:#b45309;">
                        SUPERADMIN SAAS
                    </div>
                    <a class="nav-link {{ request()->is('admin/plans*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">
                        <i class="bi bi-box-seam-fill"></i> Paket & Fitur (Plans)
                    </a>
                    <a class="nav-link {{ request()->is('admin/subscriptions*') ? 'active' : '' }}" href="{{ route('admin.subscriptions.index') }}">
                        <i class="bi bi-patch-check-fill"></i> Langganan Sekolah
                    </a>
                    <a class="nav-link {{ request()->is('admin/pages*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                        <i class="bi bi-file-earmark-richtext-fill"></i> Halaman CMS Publik
                    </a>
                    <a class="nav-link {{ request()->is('admin/waha-settings*') ? 'active' : '' }}" href="{{ route('admin.waha.index') }}">
                        <i class="bi bi-whatsapp"></i> Server WAHA (WhatsApp)
                    </a>
                    <a class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-shield-lock-fill"></i> Role & Hak Akses (RBAC)
                    </a>
                @endrole
            </nav>

            <!-- User Footer Box -->
            <div class="p-3 border-top border-slate-100 bg-slate-50 rounded-3 mx-2 mt-auto">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width:36px; height:36px; background-color:#0f766e; color:#ffffff;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden" style="max-width:120px;">
                            <div class="fw-semibold text-truncate small" style="color:#0f172a;">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-truncate" style="font-size:0.72rem; color:#64748b;">{{ Auth::user()->roles->first()?->name ?? 'User' }}</div>
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
        <header class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom border-slate-200">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h4 class="fw-bold m-0" style="color:#0f172a;">@yield('page_title', 'Dashboard')</h4>
                    <p class="text-muted small m-0">{{ Auth::user()->school->name ?? 'SekolahKu Platform' }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @php
                    $tenant = Auth::user()->tenant ?? Auth::user()->school?->tenant;
                    $planName = $tenant?->subscriptionPlan?->name ?? 'Free Plan';
                    $planCode = $tenant?->subscriptionPlan?->code ?? 'free';
                    $badgeBg = match($planCode) {
                        'pro' => 'bg-teal-700 text-white',
                        'enterprise' => 'bg-sky-700 text-white',
                        default => 'bg-secondary text-white'
                    };
                @endphp
                <span class="badge {{ $badgeBg }} px-3 py-2 rounded-pill font-monospace shadow-sm" style="font-size:0.8rem; background-color:#0f766e; color:#ffffff;">
                    <i class="bi bi-star-fill me-1"></i> {{ strtoupper($planName) }}
                </span>
                <span class="badge rounded-pill px-3 py-2" style="background-color:#f0fdfa; color:#115e59; border:1px solid #ccfbf1;">
                    <i class="bi bi-buildings me-1"></i> {{ Auth::user()->school->jenjang ?? 'TK/PAUD' }}
                </span>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert" style="background-color:#ecfdf5; color:#065f46; border:1px solid #a7f3d0;">
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

