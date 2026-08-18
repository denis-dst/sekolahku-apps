<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $page->meta_description ?? 'Hubungi Tim SekolahKu-Apps dan DnD Tech Solutions' }}">
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
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-600: #475569;
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

        .contact-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            padding: 2rem;
            height: 100%;
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 1rem;
            background: var(--teal-50);
            color: var(--teal-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
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
                        <i class="bi bi-headset me-1"></i> LAYANAN DUKUNGAN & KONTAK
                    </span>
                    <h1 class="display-5 fw-extrabold text-white mb-3">{{ $page->title }}</h1>
                    <p class="lead text-white-50 max-w-2xl mx-auto">
                        Ada pertanyaan seputar paket langganan, fitur SIM sekolah, atau butuh bantuan teknis? Tim DnD Tech Solutions siap merespon pertanyaan Anda.
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row g-4 mb-5">
            <!-- Contact Card: WhatsApp -->
            <div class="col-md-4">
                <div class="contact-card text-center">
                    <div class="icon-box mx-auto"><i class="bi bi-whatsapp"></i></div>
                    <h5 class="fw-bold text-dark">WhatsApp Support</h5>
                    <p class="small text-muted mb-3">Konsultasi cepat & tanya jawab seputar produk via WhatsApp</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $page->contact_phone ?? '6283878537818') }}" target="_blank" class="btn btn-outline-success rounded-pill px-4 fw-bold w-100">
                        <i class="bi bi-whatsapp me-1"></i> +{{ $page->contact_phone ?? '6283878537818' }}
                    </a>
                </div>
            </div>

            <!-- Contact Card: Email -->
            <div class="col-md-4">
                <div class="contact-card text-center">
                    <div class="icon-box mx-auto"><i class="bi bi-envelope-fill"></i></div>
                    <h5 class="fw-bold text-dark">Email Resmi</h5>
                    <p class="small text-muted mb-3">Kirimkan penawaran resmi, dokumen kerja sama, atau keluhan</p>
                    <a href="mailto:{{ $page->contact_email ?? 'support@dndtech.id' }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold w-100">
                        <i class="bi bi-envelope me-1"></i> {{ $page->contact_email ?? 'support@dndtech.id' }}
                    </a>
                </div>
            </div>

            <!-- Contact Card: Alamat -->
            <div class="col-md-4">
                <div class="contact-card text-center">
                    <div class="icon-box mx-auto"><i class="bi bi-geo-alt-fill"></i></div>
                    <h5 class="fw-bold text-dark">Lokasi Kantor</h5>
                    <p class="small text-muted mb-3">{{ $page->contact_address ?? 'Surabaya, Jawa Timur, Indonesia' }}</p>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="bi bi-building me-1"></i> DnD Tech Solutions</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Information & Details -->
            <div class="col-lg-6">
                <div class="contact-card">
                    <h4 class="fw-bold text-dark mb-3"><i class="bi bi-chat-left-dots-fill text-teal me-2" style="color:#0f766e;"></i>Informasi Layanan</h4>
                    <p class="text-secondary mb-4">{{ $page->content }}</p>

                    <div class="p-3 rounded-3 bg-light border mb-3">
                        <div class="fw-bold text-dark small"><i class="bi bi-clock me-2 text-primary"></i>Jam Operasional Layanan Support:</div>
                        <div class="small text-muted mt-1">Senin – Sabtu: 08:00 – 17:00 WIB (Kecuali Hari Libur Nasional)</div>
                    </div>

                    <div class="p-3 rounded-3 bg-light border">
                        <div class="fw-bold text-dark small"><i class="bi bi-shield-check me-2 text-success"></i>Respons Cepat:</div>
                        <div class="small text-muted mt-1">Pesan melalui WhatsApp akan direspon oleh tim teknis kami dalam waktu &lt;15 menit pada jam kerja.</div>
                    </div>
                </div>
            </div>

            <!-- Interactive Map Embed -->
            <div class="col-lg-6">
                <div class="contact-card p-3">
                    <h5 class="fw-bold text-dark mb-3 px-2"><i class="bi bi-map me-2 text-danger"></i>Peta Lokasi Kantor</h5>
                    @if($page->contact_maps_embed)
                        <div class="overflow-hidden rounded-4">
                            {!! $page->contact_maps_embed !!}
                        </div>
                    @else
                        <div class="p-5 text-center text-muted bg-light rounded-4">
                            <i class="bi bi-geo-alt fs-1 d-block mb-2 text-secondary"></i>
                            Peta lokasi belum disematkan oleh admin.
                        </div>
                    @endif
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
