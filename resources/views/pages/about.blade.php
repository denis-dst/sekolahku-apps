<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $page->meta_description ?? 'Tentang SekolahKu-Apps dan DnD Tech Solutions' }}">
    <title>{{ $page->title }} — SekolahKu-Apps | DnD Tech Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --teal-700: #0f766e;
            --teal-800: #115e59;
            --teal-50: #f0fdfa;
            --sky-700: #0369a1;
            --sky-50: #f0f9ff;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--slate-50);
            color: var(--slate-700);
            line-height: 1.7;
        }

        .header-hero {
            background: linear-gradient(135deg, #115e59 0%, #0f766e 50%, #0369a1 100%);
            color: #ffffff;
            padding: 80px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .header-hero::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: 0;
            right: 0;
            height: 80px;
            background: var(--slate-50);
            transform: skewY(-2deg);
        }

        .navbar-brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            padding: 2.5rem;
        }

        .btn-teal {
            background-color: var(--teal-700);
            color: #ffffff;
            border-radius: 0.75rem;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .btn-teal:hover {
            background-color: var(--teal-800);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .footer {
            background: var(--slate-900);
            color: #cbd5e1;
            padding: 40px 0 20px;
            margin-top: 80px;
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
        }

        .footer a:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <header class="header-hero">
        <div class="container">
            <nav class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ url('/') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
                    <div class="navbar-brand-mark"><i class="bi bi-mortarboard-fill"></i></div>
                    <span class="fw-bold fs-4">SekolahKu-Apps</span>
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">Kembali ke Beranda</a>
                    <a href="{{ url('/login') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold">Masuk Dashboard</a>
                </div>
            </nav>

            <div class="row justify-content-center text-center py-4">
                <div class="col-lg-9">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill font-monospace mb-3 shadow-sm" style="color:#0f766e!important;">
                        <i class="bi bi-info-circle-fill me-1"></i> TENTANG PLATFORM
                    </span>
                    <h1 class="display-5 fw-extrabold text-white mb-3">{{ $page->title }}</h1>
                    <p class="lead text-white-50 max-w-2xl mx-auto">
                        Mengenal lebih dekat visi, misi, dan tim di balik pengembangan SekolahKu-Apps — produk teknologi edukasi unggulan DnD Tech Solutions.
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="content-card mb-5">
                    <h3 class="fw-bold text-dark mb-4" style="color:#0f172a;">Profil & Visi Perusahaan</h3>
                    
                    <div class="fs-6 text-secondary mb-4" style="white-space: pre-line;">
                        {{ $page->content }}
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border text-center h-100">
                                <div class="fs-1 text-teal mb-2" style="color:#0f766e;"><i class="bi bi-rocket-takeoff-fill"></i></div>
                                <h5 class="fw-bold text-dark">Inovatif & Cerdas</h5>
                                <p class="small text-muted mb-0">Mengintegrasikan fitur presensi WA, SPP QRIS, & LPJ BOSP otomatis.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border text-center h-100">
                                <div class="fs-1 text-sky mb-2" style="color:#0369a1;"><i class="bi bi-shield-check"></i></div>
                                <h5 class="fw-bold text-dark">Aman & Handal</h5>
                                <p class="small text-muted mb-0">Perlindungan data sekolah berbasis multi-tenant & hak akses terenkripsi.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border text-center h-100">
                                <div class="fs-1 text-success mb-2" style="color:#047857;"><i class="bi bi-heart-fill"></i></div>
                                <h5 class="fw-bold text-dark">Dukungan Ramah</h5>
                                <p class="small text-muted mb-0">Layanan konsultasi dan bantuan cepat langsung oleh tim teknis kami.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner CTA -->
                <div class="p-5 rounded-4 text-white text-center shadow-sm" style="background: linear-gradient(135deg, #0f766e 0%, #0369a1 100%);">
                    <h3 class="fw-bold mb-2">Ingin Menggunakan SekolahKu-Apps di Sekolah Anda?</h3>
                    <p class="text-white-50 mb-4">Mulai gratis sekarang atau hubungi tim kami untuk konsultasi alur kerja sekolah Anda.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('pages.contact') }}" class="btn btn-light rounded-pill px-4 fw-bold">Hubungi Kami</a>
                        <a href="{{ url('/login') }}" class="btn btn-outline-light rounded-pill px-4">Coba Fitur Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-2">&copy; {{ date('Y') }} SekolahKu-Apps — Produk DnD Tech Solutions. Hak Cipta Dilindungi.</p>
            <div class="small">
                <a href="{{ url('/') }}" class="me-3">Beranda</a>
                <a href="{{ route('pages.about') }}" class="me-3">Tentang Kami</a>
                <a href="{{ route('pages.contact') }}" class="me-3">Hubungi Kami</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
