<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingetin Admin & REST API Hub — SekolahKu</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-primary: #059669;
            --brand-neon: #10b981;
            --brand-accent: #34d399;
            --brand-bg: #091312;
            --card-glass: rgba(15, 23, 22, 0.92);
            --card-border: rgba(16, 185, 129, 0.18);
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--brand-bg);
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(5, 150, 105, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(16, 185, 129, 0.12) 0%, transparent 40%);
            min-height: 100vh;
            color: #f1f5f9;
        }

        .navbar-custom {
            background: rgba(10, 20, 19, 0.9);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--card-border);
        }

        .brand-logo-mark {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #10b981, #047857);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
        }

        .card-custom {
            background: var(--card-glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            transition: all 0.25s ease;
        }

        .card-custom:hover {
            border-color: rgba(16, 185, 129, 0.35);
        }

        .stat-card {
            padding: 1.25rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #10b981, #059669);
        }

        .stat-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
        }

        .nav-tabs-custom {
            border-bottom: 1px solid rgba(16, 185, 129, 0.2);
            gap: 0.5rem;
        }

        .nav-tabs-custom .nav-link {
            color: #94a3b8;
            border: none;
            border-radius: 0.75rem 0.75rem 0 0;
            padding: 0.85rem 1.35rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: transparent;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #e2e8f0;
            background: rgba(16, 185, 129, 0.08);
        }

        .nav-tabs-custom .nav-link.active {
            color: #34d399;
            background: rgba(16, 185, 129, 0.15);
            border-bottom: 3px solid #10b981;
        }

        .btn-neon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            border-radius: 0.65rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
        }

        .btn-neon:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
            color: #ffffff;
        }

        .form-control, .form-select {
            background-color: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(148, 163, 184, 0.25);
            color: #f8fafc;
            border-radius: 0.65rem;
            font-size: 0.9rem;
            padding: 0.65rem 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(15, 23, 42, 0.95);
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
            color: #ffffff;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .arabic-text {
            font-family: 'Amiri', serif;
            font-size: 1.55rem;
            line-height: 1.9;
            direction: rtl;
            text-align: right;
            color: #a7f3d0;
        }

        .badge-cat {
            font-size: 0.72rem;
            padding: 0.35rem 0.65rem;
            border-radius: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pagi { background: rgba(245, 158, 11, 0.2); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-petang { background: rgba(147, 51, 234, 0.2); color: #d8b4fe; border: 1px solid rgba(147, 51, 234, 0.3); }
        .badge-tidur { background: rgba(59, 130, 246, 0.2); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-lainnya { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3); }

        .table-custom {
            --bs-table-bg: transparent;
            --bs-table-color: #e2e8f0;
            --bs-table-border-color: rgba(148, 163, 184, 0.15);
        }

        .table-custom th {
            color: #94a3b8;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            background: rgba(15, 23, 42, 0.4);
            border-bottom: 1px solid rgba(16, 185, 129, 0.2);
            padding: 0.85rem 1rem;
        }

        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }

        .api-pill {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 0.75rem;
            padding: 1rem;
            transition: all 0.2s;
        }

        .api-pill:hover {
            border-color: #10b981;
            background: rgba(15, 23, 42, 0.9);
        }

        .modal-content {
            background: #0f1c1a;
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 1.25rem;
            color: #f1f5f9;
        }

        .modal-header {
            border-bottom: 1px solid rgba(16, 185, 129, 0.2);
        }

        .modal-footer {
            border-top: 1px solid rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-lg-4">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-logo-mark">
                    <i class="bi bi-moon-stars-fill text-white"></i>
                </div>
                <div>
                    <h5 class="m-0 fw-bold d-flex align-items-center gap-2" style="font-family: 'Outfit';">
                        <span>Ingetin</span>
                        <span class="badge bg-emerald-900 text-emerald-300 border border-emerald-700" style="font-size:0.65rem; background:rgba(16,185,129,0.2); color:#6ee7b7; border-color:rgba(16,185,129,0.4) !important;">API & Backend</span>
                    </h5>
                    <small class="text-muted" style="font-size: 0.72rem;">Terintegrasi di SekolahKu Apps Engine</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary text-slate-300 border-slate-700 rounded-pill px-3" style="color: #cbd5e1;">
                    <i class="bi bi-mortarboard me-1"></i> Dashboard SekolahKu
                </a>
                <a href="{{ route('ingetin.logout') }}" class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3" title="Keluar">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container-fluid px-lg-4 py-4">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4" style="background: rgba(16, 185, 129, 0.18); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3) !important;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-4" style="background: rgba(239, 68, 68, 0.18); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3) !important;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Top Metric KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card-custom stat-card">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Sedekah Terkumpul</small>
                    <div class="stat-val mt-1">Rp {{ $stats->total_amount_text }}</div>
                    <small class="text-emerald-400 d-flex align-items-center gap-1 mt-1" style="color: #34d399; font-size: 0.75rem;">
                        <i class="bi bi-heart-fill"></i> Dari {{ number_format($stats->total_donors) }} donatur
                    </small>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card-custom stat-card">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Total Dzikir & Doa</small>
                    <div class="stat-val mt-1">{{ count($allDzikir) }}</div>
                    <small class="text-slate-400 mt-1 d-block" style="font-size: 0.75rem;">Pagi, Petang, Tidur & Sunnah</small>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card-custom stat-card">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Hadits Harian</small>
                    <div class="stat-val mt-1">{{ count($allHadith) }}</div>
                    <small class="text-slate-400 mt-1 d-block" style="font-size: 0.75rem;">Rotasi otomatis harian</small>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card-custom stat-card">
                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Status REST API</small>
                    <div class="stat-val text-emerald-400 mt-1" style="color: #34d399;">ONLINE</div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Siap diakses Mobile App</small>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="qris-tab" data-bs-toggle="tab" data-bs-target="#tab-qris" type="button" role="tab">
                    <i class="bi bi-qr-code me-1"></i> QRIS & Sedekah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="dzikir-tab" data-bs-toggle="tab" data-bs-target="#tab-dzikir" type="button" role="tab">
                    <i class="bi bi-book-half me-1"></i> Dzikir & Doa Sunnah ({{ count($allDzikir) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="hadith-tab" data-bs-toggle="tab" data-bs-target="#tab-hadith" type="button" role="tab">
                    <i class="bi bi-chat-quote-fill me-1"></i> Hadits Harian ({{ count($allHadith) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="api-tab" data-bs-toggle="tab" data-bs-target="#tab-api" type="button" role="tab">
                    <i class="bi bi-hdd-network-fill me-1"></i> REST API & Hosting Hub
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="mainTabContent">

            <!-- TAB 1: QRIS & SEDEKAH -->
            <div class="tab-pane fade show active" id="tab-qris" role="tabpanel">
                <div class="row g-4">
                    <!-- Left: QRIS Card Preview -->
                    <div class="col-lg-4">
                        <div class="card-custom p-4 text-center">
                            <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-image text-emerald-400"></i> Preview QRIS Mobile
                            </h6>
                            <div class="p-3 rounded-3 mb-3 bg-white d-inline-block shadow-sm" style="max-width: 260px;">
                                <img src="{{ $qrisPreviewUrl }}" alt="QRIS Merchant" class="img-fluid rounded" style="max-height: 240px; object-fit: contain;">
                            </div>
                            <div class="fw-bold text-white fs-6">{{ $settings['merchant_name'] }}</div>
                            <small class="text-muted d-block">{{ $settings['merchant_city'] }}</small>
                            <p class="text-slate-300 small mt-3 fst-italic px-2" style="font-size: 0.82rem;">
                                "{{ $settings['donation_info'] }}"
                            </p>
                        </div>
                    </div>

                    <!-- Right: Form Settings & Stats -->
                    <div class="col-lg-8">
                        <!-- QRIS & Merchant Form -->
                        <div class="card-custom p-4 mb-4">
                            <h5 class="fw-bold mb-3 text-emerald-300" style="color: #6ee7b7;">
                                <i class="bi bi-pencil-square me-2"></i> Pengaturan Informasi Merchant & Foto QRIS
                            </h5>
                            <form action="{{ route('ingetin.settings.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Nama Merchant / Yayasan QRIS</label>
                                        <input type="text" name="merchant_name" class="form-control" value="{{ $settings['merchant_name'] }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Kota Merchant</label>
                                        <input type="text" name="merchant_city" class="form-control" value="{{ $settings['merchant_city'] }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-muted">Unggah File QRIS Baru (PNG / JPG / WebP)</label>
                                        <input type="file" name="qris_file" class="form-control" accept="image/*">
                                        <small class="text-muted" style="font-size: 0.75rem;">Kosongkan jika tidak ingin mengubah gambar QRIS saat ini.</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-muted">Pesan Sedekah di Aplikasi Mobile</label>
                                        <textarea name="donation_info" class="form-control" rows="2">{{ $settings['donation_info'] }}</textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-neon">
                                            <i class="bi bi-save me-1"></i> Simpan Merchant & QRIS
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Update Donation Stats -->
                        <div class="card-custom p-4">
                            <h5 class="fw-bold mb-3 text-emerald-300" style="color: #6ee7b7;">
                                <i class="bi bi-bar-chart-fill me-2"></i> Perbarui Statistik Sedekah Komunitas
                            </h5>
                            <form action="{{ route('ingetin.stats.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Total Donatur</label>
                                        <input type="number" name="total_donors" class="form-control" value="{{ $stats->total_donors }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Total Dana Teks (cth: 47.2jt)</label>
                                        <input type="text" name="total_amount_text" class="form-control" value="{{ $stats->total_amount_text }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Program Aktif</label>
                                        <input type="number" name="total_programs" class="form-control" value="{{ $stats->total_programs }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small text-muted">Catatan Transparansi Donasi</label>
                                        <input type="text" name="note" class="form-control" value="{{ $stats->note }}">
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-neon">
                                            <i class="bi bi-save me-1"></i> Perbarui Statistik Sedekah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: DZIKIR & DOA -->
            <div class="tab-pane fade" id="tab-dzikir" role="tabpanel">
                <div class="card-custom p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                        <div>
                            <h5 class="fw-bold m-0 text-emerald-300" style="color: #6ee7b7;">Koleksi Dzikir & Doa Harian</h5>
                            <small class="text-muted">Data ini disinkronkan langsung ke aplikasi Android Ingetin.</small>
                        </div>
                        <button class="btn btn-neon" data-bs-toggle="modal" data-bs-target="#modalAddDzikir">
                            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Dzikir Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Kategori</th>
                                    <th>Judul & Teks Arab</th>
                                    <th style="width: 120px;">Target</th>
                                    <th>Sumber Riwayat</th>
                                    <th style="width: 120px;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allDzikir as $dzikir)
                                    <tr>
                                        <td>
                                            <span class="badge-cat badge-{{ $dzikir->category }}">{{ $dzikir->category }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-white mb-1">{{ $dzikir->title }}</div>
                                            <div class="arabic-text mb-2">{{ $dzikir->arabic }}</div>
                                            <div class="small fst-italic text-slate-400 mb-1">{{ $dzikir->latin }}</div>
                                            <div class="small text-slate-300">{{ $dzikir->translation }}</div>
                                            @if($dzikir->benefit)
                                                <div class="text-xs text-emerald-400 mt-1" style="color: #34d399; font-size: 0.76rem;">
                                                    <i class="bi bi-star-fill me-1"></i> Keutamaan: {{ $dzikir->benefit }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-slate-800 border border-slate-700 text-white px-2 py-1">
                                                {{ $dzikir->target_count }}x bacaan
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $dzikir->source ?? '-' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn btn-sm btn-outline-info rounded-2" 
                                                        onclick="editDzikir({{ json_encode($dzikir) }})" 
                                                        title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <form action="{{ route('ingetin.dzikir.delete', $dzikir->id) }}" method="POST" onsubmit="return confirm('Hapus dzikir ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data dzikir.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: HADITS HARIAN -->
            <div class="tab-pane fade" id="tab-hadith" role="tabpanel">
                <div class="card-custom p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                        <div>
                            <h5 class="fw-bold m-0 text-emerald-300" style="color: #6ee7b7;">Daftar Hadits Harian</h5>
                            <small class="text-muted">Aplikasi mobile mengambil hadits pilihan bergilir secara harian.</small>
                        </div>
                        <button class="btn btn-neon" data-bs-toggle="modal" data-bs-target="#modalAddHadith">
                            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Hadits Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Isi Matan / Terjemahan Hadits</th>
                                    <th style="width: 180px;">Perawi</th>
                                    <th style="width: 160px;">Topik</th>
                                    <th style="width: 100px;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allHadith as $hadith)
                                    <tr>
                                        <td>#{{ $hadith->id }}</td>
                                        <td>
                                            <div class="text-white fs-6">"{{ $hadith->text }}"</div>
                                        </td>
                                        <td class="text-emerald-400 fw-semibold" style="color: #34d399;">{{ $hadith->narrator }}</td>
                                        <td>
                                            <span class="badge bg-slate-800 border border-slate-700 text-slate-300 px-2 py-1">
                                                {{ $hadith->topic ?? 'Umum' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button class="btn btn-sm btn-outline-info rounded-2" 
                                                        onclick="editHadith({{ json_encode($hadith) }})" 
                                                        title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <form action="{{ route('ingetin.hadith.delete', $hadith->id) }}" method="POST" onsubmit="return confirm('Hapus hadits ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data hadits.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: REST API & HOSTING HUB -->
            <div class="tab-pane fade" id="tab-api" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card-custom p-4 mb-4">
                            <h5 class="fw-bold mb-3 text-emerald-300" style="color: #6ee7b7;">
                                <i class="bi bi-hdd-network me-2"></i> Endpoint REST API Siap Hosting
                            </h5>
                            <p class="small text-muted mb-3">
                                Endpoint ini menyajikan format JSON murni dengan CORS aktif, kompatibel 100% dengan aplikasi Android Ingetin dan sistem lain.
                            </p>

                            <!-- Endpoint 1: Settings -->
                            <div class="api-pill mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success px-2 py-1">GET</span>
                                        <code class="text-emerald-300 fw-bold">/ingetin/api/settings</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/settings') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="color: #93c5fd; border-color: rgba(147, 197, 253, 0.4);">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka API
                                    </a>
                                </div>
                                <small class="text-muted">Mengembalikan konfigurasi app, URL QRIS aktif, info merchant, & statistik sedekah.</small>
                            </div>

                            <!-- Endpoint 2: Dzikir -->
                            <div class="api-pill mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success px-2 py-1">GET</span>
                                        <code class="text-emerald-300 fw-bold">/ingetin/api/dzikir?category=pagi</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/dzikir?category=pagi') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="color: #93c5fd; border-color: rgba(147, 197, 253, 0.4);">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka API
                                    </a>
                                </div>
                                <small class="text-muted">Daftar dzikir dan doa harian lengkap dengan teks Arab RTL, Latin, dan terjemahan.</small>
                            </div>

                            <!-- Endpoint 3: Hadith -->
                            <div class="api-pill mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success px-2 py-1">GET</span>
                                        <code class="text-emerald-300 fw-bold">/ingetin/api/hadith</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/hadith') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="color: #93c5fd; border-color: rgba(147, 197, 253, 0.4);">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka API
                                    </a>
                                </div>
                                <small class="text-muted">Hadits motivasi harian pilihan rotasi otomatis sesuai tanggal kalender.</small>
                            </div>

                            <!-- Endpoint 4: Prayers -->
                            <div class="api-pill">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success px-2 py-1">GET</span>
                                        <code class="text-emerald-300 fw-bold">/ingetin/api/prayers?lat=-6.2088&lng=106.8456</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/prayers?lat=-6.2088&lng=106.8456') }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="color: #93c5fd; border-color: rgba(147, 197, 253, 0.4);">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka API
                                    </a>
                                </div>
                                <small class="text-muted">Perhitungan astronomis hisab sholat 5 waktu & waktu Dhuha standar Kemenag RI.</small>
                            </div>
                        </div>

                        <!-- Hosting Guide Note -->
                        <div class="card-custom p-4">
                            <h6 class="fw-bold mb-2 text-white">
                                <i class="bi bi-globe me-2 text-emerald-400"></i> Panduan Hosting ke Server Online
                            </h6>
                            <p class="small text-slate-300 mb-2">
                                Ketika Anda meng-upload project SekolahKu ke VPS atau cPanel (misal: <code>https://sekolahku.id</code>):
                            </p>
                            <ol class="small text-muted ps-3 mb-0">
                                <li class="mb-1">URL API otomatis menjadi: <code>https://domainanda.com/ingetin/api/</code></li>
                                <li class="mb-1">Pengaturan admin dapat diakses di: <code>https://domainanda.com/ingetin/login</code></li>
                                <li>Buka aplikasi Ingetin di HP Android Anda &rarr; Masuk ke menu <strong>Profil & Pengaturan</strong> &rarr; masukkan URL hosting Anda tersebut.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Right: Prayer Times & Admin PIN -->
                    <div class="col-lg-5">
                        <div class="card-custom p-4 mb-4">
                            <h6 class="fw-bold mb-3 text-emerald-300" style="color: #6ee7b7;">
                                <i class="bi bi-clock-fill me-2"></i> Jadwal Sholat Dhuha Default
                            </h6>
                            <form action="{{ route('ingetin.settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Mulai Dhuha</label>
                                        <input type="text" name="dhuha_start_time" class="form-control" value="{{ $settings['dhuha_start_time'] }}" placeholder="07:00">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Selesai Dhuha</label>
                                        <input type="text" name="dhuha_end_time" class="form-control" value="{{ $settings['dhuha_end_time'] }}" placeholder="11:00">
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-sm btn-neon">Simpan Jadwal</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-custom p-4">
                            <h6 class="fw-bold mb-3 text-emerald-300" style="color: #6ee7b7;">
                                <i class="bi bi-shield-lock-fill me-2"></i> Ganti PIN Admin Ingetin
                            </h6>
                            <form action="{{ route('ingetin.pin.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small text-muted">PIN Baru (Minimal 4 Angka)</label>
                                    <input type="password" name="new_pin" class="form-control" placeholder="••••••" required maxlength="8">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-neon">Ubah PIN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL: ADD / EDIT DZIKIR -->
    <div class="modal fade" id="modalAddDzikir" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('ingetin.dzikir.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="dzikir_id" value="0">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="dzikirModalTitle">Tambah Dzikir Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Kategori</label>
                                <select name="category" id="dzikir_category" class="form-select" required>
                                    <option value="pagi">Dzikir Pagi</option>
                                    <option value="petang">Dzikir Petang</option>
                                    <option value="tidur">Sebelum Tidur</option>
                                    <option value="lainnya">Lainnya / Sunnah</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Target Hitungan Bacaan</label>
                                <input type="number" name="target_count" id="dzikir_target" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Judul Amalan / Doa</label>
                                <input type="text" name="title" id="dzikir_title" class="form-control" placeholder="Contoh: Membaca Ayat Kursi" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Teks Arab (RTL)</label>
                                <textarea name="arabic" id="dzikir_arabic" class="form-control arabic-text" rows="3" required placeholder="اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Transliterasi Latin</label>
                                <textarea name="latin" id="dzikir_latin" class="form-control" rows="2" placeholder="Allahu laa ilaaha illaa huwal hayyul qayyuum..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Terjemahan Bahasa Indonesia</label>
                                <textarea name="translation" id="dzikir_translation" class="form-control" rows="3" placeholder="Allah, tidak ada tuhan selain Dia..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Keutamaan / Manfaat Amalan</label>
                                <input type="text" name="benefit" id="dzikir_benefit" class="form-control" placeholder="Cth: Dilindungi dari gangguan jin hingga petang">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Sumber Riwayat Hadits</label>
                                <input type="text" name="source" id="dzikir_source" class="form-control" placeholder="Cth: HR. An-Nasa'i & Al-Hakim">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Urutan Tampil (Index)</label>
                                <input type="number" name="order_index" id="dzikir_order" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="dzikir_active" value="1" checked>
                                    <label class="form-check-label text-slate-300 small" for="dzikir_active">
                                        Aktifkan di Aplikasi Mobile
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-neon">Simpan Dzikir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: ADD / EDIT HADITH -->
    <div class="modal fade" id="modalAddHadith" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('ingetin.hadith.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="hadith_id" value="0">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="hadithModalTitle">Tambah Hadits Harian</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Isi Matan / Pesan Hadits</label>
                            <textarea name="text" id="hadith_text" class="form-control" rows="4" required placeholder="Sesungguhnya amalan yang paling dicintai oleh Allah..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Perawi / Riwayat</label>
                            <input type="text" name="narrator" id="hadith_narrator" class="form-control" placeholder="HR. Bukhari & Muslim" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Topik / Tema Hadits</label>
                            <input type="text" name="topic" id="hadith_topic" class="form-control" placeholder="Konsistensi Ibadah">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="hadith_active" value="1" checked>
                            <label class="form-check-label text-slate-300 small" for="hadith_active">
                                Aktifkan di Rotasi Harian
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-neon">Simpan Hadits</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editDzikir(data) {
            document.getElementById('dzikirModalTitle').innerText = 'Edit Dzikir #' + data.id;
            document.getElementById('dzikir_id').value = data.id;
            document.getElementById('dzikir_category').value = data.category;
            document.getElementById('dzikir_target').value = data.target_count;
            document.getElementById('dzikir_title').value = data.title;
            document.getElementById('dzikir_arabic').value = data.arabic;
            document.getElementById('dzikir_latin').value = data.latin || '';
            document.getElementById('dzikir_translation').value = data.translation || '';
            document.getElementById('dzikir_benefit').value = data.benefit || '';
            document.getElementById('dzikir_source').value = data.source || '';
            document.getElementById('dzikir_order').value = data.order_index || 0;
            document.getElementById('dzikir_active').checked = (data.is_active == 1);
            
            new bootstrap.Modal(document.getElementById('modalAddDzikir')).show();
        }

        function editHadith(data) {
            document.getElementById('hadithModalTitle').innerText = 'Edit Hadits #' + data.id;
            document.getElementById('hadith_id').value = data.id;
            document.getElementById('hadith_text').value = data.text;
            document.getElementById('hadith_narrator').value = data.narrator;
            document.getElementById('hadith_topic').value = data.topic || '';
            document.getElementById('hadith_active').checked = (data.is_active == 1);

            new bootstrap.Modal(document.getElementById('modalAddHadith')).show();
        }

        // Reset modal on close
        document.getElementById('modalAddDzikir').addEventListener('hidden.bs.modal', function () {
            document.getElementById('dzikirModalTitle').innerText = 'Tambah Dzikir Baru';
            document.getElementById('dzikir_id').value = '0';
        });

        document.getElementById('modalAddHadith').addEventListener('hidden.bs.modal', function () {
            document.getElementById('hadithModalTitle').innerText = 'Tambah Hadits Harian';
            document.getElementById('hadith_id').value = '0';
        });
    </script>
</body>
</html>
