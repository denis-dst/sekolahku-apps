<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SekolahKu SaaS') - SIM & Asisten Digital Finance Sekolah</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- ApexCharts -->
    <script href="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        :root {
            --sk-primary: #4f46e5;
            --sk-primary-hover: #4338ca;
            --sk-secondary: #06b6d4;
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

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);
            color: #f8fafc;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0.2rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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

        .kpi-card {
            border-left: 5rem solid var(--sk-primary);
        }

        .kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .badge-status {
            padding: 0.4em 0.8em;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.75rem;
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
        <div class="p-3 d-flex align-items-center gap-2 border-bottom border-secondary border-opacity-25">
            <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                <i class="bi bi-mortarboard-fill fs-4"></i>
            </div>
            <div>
                <h5 class="m-0 fw-bold text-white">SekolahKu</h5>
                <small class="text-xs text-primary-subtle">SaaS SIM & Finance</small>
            </div>
        </div>

        <div class="px-3 py-2 text-uppercase text-xs fw-bold text-slate-400 opacity-75 mt-2" style="font-size:0.7rem; letter-spacing:1px;">
            NAVIGASI UTAMA
        </div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            @role('Siswa')
                <a class="nav-link {{ request()->is('presensi/mandiri') ? 'active' : '' }}" href="{{ route('presensi.mandiri') }}">
                    <i class="bi bi-qr-code-scan"></i> Absen Mandiri
                </a>
            @endrole

            @hasanyrole('Superadmin|School Admin|Guru')
                <a class="nav-link {{ request()->is('presensi') && !request()->is('presensi/mandiri') ? 'active' : '' }}" href="{{ route('presensi.index') }}">
                    <i class="bi bi-calendar-check-fill"></i> Presensi Kelas
                </a>
            @endhasanyrole

            @hasanyrole('Superadmin|School Admin|Bendahara|Orang Tua|Siswa')
                <a class="nav-link {{ request()->is('spp*') ? 'active' : '' }}" href="{{ route('spp.index') }}">
                    <i class="bi bi-wallet2"></i> Pembayaran SPP
                </a>
            @endhasanyrole

            @hasanyrole('Superadmin|School Admin|Bendahara')
                <a class="nav-link {{ request()->is('spp/verifikasi*') ? 'active' : '' }}" href="{{ route('spp.verifikasi.queue') }}">
                    <i class="bi bi-check2-circle"></i> Verifikasi SPP
                </a>
            @endhasanyrole

            @hasanyrole('Superadmin|School Admin|Bendahara|Guru')
                <a class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="bi bi-cash-stack"></i> BendaharaKu / LPJ
                </a>
            @endhasanyrole

            @hasanyrole('Superadmin|School Admin|Guru|Orang Tua|Siswa')
                <a class="nav-link {{ request()->is('anekdot*') ? 'active' : '' }}" href="{{ route('anekdot.index') }}">
                    <i class="bi bi-journal-bookmark-fill"></i> Catatan Anekdot
                </a>
                <a class="nav-link {{ request()->is('erapor*') ? 'active' : '' }}" href="{{ route('erapor.index') }}">
                    <i class="bi bi-file-earmark-pdf-fill"></i> E-Rapor Digital
                </a>
            @endhasanyrole

            <div class="px-3 py-2 text-uppercase text-xs fw-bold text-slate-400 opacity-75 mt-3" style="font-size:0.7rem; letter-spacing:1px;">
                MASTER & PROFIL
            </div>

            @hasanyrole('Superadmin|School Admin')
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
            @endhasanyrole
        </nav>

        <div class="position-absolute bottom-0 start-0 end-0 p-3 border-top border-secondary border-opacity-25 bg-dark">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px; height:36px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden" style="max-width:130px;">
                        <div class="fw-semibold text-white text-truncate small">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-400 text-truncate" style="font-size:0.75rem;">{{ Auth::user()->roles->first()?->name ?? 'User' }}</div>
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
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
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
    <script href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
