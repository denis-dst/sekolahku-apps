<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingetin Admin & REST API Hub — SekolahKu</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-primary: #059669;
            --brand-primary-hover: #047857;
            --brand-light: #ecfdf5;
            --brand-border: #a7f3d0;
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-body);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .brand-logo-mark {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        /* Clean White Cards */
        .card-clean {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .card-clean:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
        }

        /* KPI Stat Cards */
        .stat-card {
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .stat-card .stat-icon-badge {
            width: 44px;
            height: 44px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .stat-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-heading);
            line-height: 1.2;
        }

        /* Tabs Clean */
        .nav-tabs-clean {
            border-bottom: 2px solid var(--border-color);
            gap: 0.5rem;
        }

        .nav-tabs-clean .nav-link {
            color: var(--text-muted);
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.85rem 1.25rem;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.2s;
            background: transparent;
            margin-bottom: -2px;
        }

        .nav-tabs-clean .nav-link:hover {
            color: var(--brand-primary);
        }

        .nav-tabs-clean .nav-link.active {
            color: var(--brand-primary);
            border-bottom-color: var(--brand-primary);
            background: transparent;
        }

        /* Buttons */
        .btn-brand {
            background-color: var(--brand-primary);
            border-color: var(--brand-primary);
            color: #ffffff;
            font-weight: 600;
            border-radius: 0.65rem;
            padding: 0.6rem 1.25rem;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2);
            transition: all 0.2s;
        }

        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            border-color: var(--brand-primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .btn-brand-light {
            background-color: var(--brand-light);
            color: var(--brand-primary-hover);
            border: 1px solid var(--brand-border);
            font-weight: 600;
            border-radius: 0.65rem;
            padding: 0.45rem 1rem;
        }

        .btn-brand-light:hover {
            background-color: #d1fae5;
            color: #065f46;
        }

        /* Forms */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            border-radius: 0.65rem;
            font-size: 0.9rem;
            padding: 0.65rem 0.95rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            color: #0f172a;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Arabic Typography */
        .arabic-text {
            font-family: 'Amiri', serif;
            font-size: 1.55rem;
            line-height: 2;
            direction: rtl;
            text-align: right;
            color: #064e3b;
            background: #f0fdf4;
            padding: 0.85rem 1.25rem;
            border-radius: 0.75rem;
            border: 1px solid #dcfce7;
        }

        /* Categories Badges */
        .badge-cat {
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pagi { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-petang { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
        .badge-tidur { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .badge-lainnya { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }

        /* Tables Clean */
        .table-clean {
            --bs-table-bg: transparent;
            --bs-table-color: #334155;
            --bs-table-border-color: #f1f5f9;
        }

        .table-clean th {
            color: #475569;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 0.9rem 1rem;
        }

        .table-clean td {
            padding: 1.1rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-clean tbody tr:hover {
            background-color: #f8fafc;
        }

        /* API Card Pill */
        .api-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            padding: 1.1rem;
            transition: all 0.2s ease;
        }

        .api-pill:hover {
            border-color: var(--brand-primary);
            background: #f0fdf4;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.08);
        }

        /* Modals */
        .modal-content {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1rem 1.5rem;
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-2.5">
        <div class="container-fluid px-lg-4">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-logo-mark">
                    <i class="bi bi-moon-stars-fill"></i>
                </div>
                <div>
                    <h5 class="m-0 fw-bold d-flex align-items-center gap-2" style="font-family: 'Outfit'; color: #0f172a;">
                        <span>Ingetin</span>
                        <span class="badge" style="font-size:0.68rem; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">API & Backend</span>
                    </h5>
                    <small class="text-muted" style="font-size: 0.75rem;">Panel Kontrol & Layanan Mobile Muslim Terpadu</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-weight: 500;">
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
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4 shadow-sm" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0 !important;">
                <i class="bi bi-check-circle-fill me-2 text-emerald-600"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-4 shadow-sm" style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca !important;">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Top Metric KPI Cards -->
        <div class="row g-3 mb-4">
            <!-- Sedekah Terkumpul -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sedekah Terkumpul</small>
                        <div class="stat-icon-badge" style="background: #ecfdf5; color: #059669;">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <div class="stat-val">Rp {{ $stats->total_amount_text }}</div>
                    <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.78rem;">
                        <i class="bi bi-heart-fill text-danger"></i> Dari <strong class="text-dark">{{ number_format($stats->total_donors) }}</strong> donatur
                    </small>
                </div>
            </div>

            <!-- Dzikir & Doa -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Dzikir & Doa</small>
                        <div class="stat-icon-badge" style="background: #ede9fe; color: #7c3aed;">
                            <i class="bi bi-book-half"></i>
                        </div>
                    </div>
                    <div class="stat-val">{{ count($allDzikir) }} <span class="fs-6 fw-normal text-muted">Amalan</span></div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">Pagi, Petang, Tidur & Sunnah</small>
                </div>
            </div>

            <!-- Hadits Harian -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Hadits Harian</small>
                        <div class="stat-icon-badge" style="background: #fef3c7; color: #d97706;">
                            <i class="bi bi-chat-quote-fill"></i>
                        </div>
                    </div>
                    <div class="stat-val">{{ count($allHadith) }} <span class="fs-6 fw-normal text-muted">Hadits</span></div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">Rotasi otomatis harian</small>
                </div>
            </div>

            <!-- Status REST API -->
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Status REST API</small>
                        <div class="stat-icon-badge" style="background: #dcfce7; color: #15803d;">
                            <i class="bi bi-broadcast"></i>
                        </div>
                    </div>
                    <div class="stat-val text-success">ONLINE</div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">Tersinkronisasi Database MySQL</small>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-clean mb-4" id="mainTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="qris-tab" data-bs-toggle="tab" data-bs-target="#tab-qris" type="button" role="tab">
                    <i class="bi bi-qr-code me-1.5"></i> QRIS & Sedekah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="dzikir-tab" data-bs-toggle="tab" data-bs-target="#tab-dzikir" type="button" role="tab">
                    <i class="bi bi-book me-1.5"></i> Dzikir & Doa Sunnah ({{ count($allDzikir) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="hadith-tab" data-bs-toggle="tab" data-bs-target="#tab-hadith" type="button" role="tab">
                    <i class="bi bi-chat-quote me-1.5"></i> Hadits Harian ({{ count($allHadith) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="api-tab" data-bs-toggle="tab" data-bs-target="#tab-api" type="button" role="tab">
                    <i class="bi bi-hdd-network me-1.5"></i> REST API & Hosting Hub
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
                        <div class="card-clean p-4 text-center">
                            <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" style="color: #0f172a;">
                                <i class="bi bi-image text-emerald-600"></i> Preview QRIS Mobile
                            </h6>
                            <div class="p-3 rounded-3 mb-3 bg-light border d-inline-block shadow-xs" style="max-width: 260px;">
                                <img src="{{ $qrisPreviewUrl }}" alt="QRIS Merchant" class="img-fluid rounded" style="max-height: 240px; object-fit: contain;">
                            </div>
                            <div class="fw-bold fs-6 text-dark">{{ $settings['merchant_name'] }}</div>
                            <small class="text-muted d-block">{{ $settings['merchant_city'] }}</small>
                            <p class="text-secondary small mt-3 fst-italic px-2" style="font-size: 0.85rem;">
                                "{{ $settings['donation_info'] }}"
                            </p>
                        </div>
                    </div>

                    <!-- Right: Form Settings & Stats -->
                    <div class="col-lg-8">
                        <!-- QRIS & Merchant Form -->
                        <div class="card-clean p-4 mb-4">
                            <h5 class="fw-bold mb-3" style="color: #0f172a;">
                                <i class="bi bi-pencil-square text-emerald-600 me-2"></i> Pengaturan Informasi Merchant & Foto QRIS
                            </h5>
                            <form action="{{ route('ingetin.settings.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Merchant / Yayasan QRIS</label>
                                        <input type="text" name="merchant_name" class="form-control" value="{{ $settings['merchant_name'] }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kota Merchant</label>
                                        <input type="text" name="merchant_city" class="form-control" value="{{ $settings['merchant_city'] }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Unggah Foto QRIS Baru (PNG / JPG / WebP)</label>
                                        <input type="file" name="qris_file" class="form-control" accept="image/*">
                                        <small class="text-muted" style="font-size: 0.78rem;">Kosongkan jika tidak ingin mengubah gambar QRIS saat ini.</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Pesan Ajakan Sedekah di Aplikasi Mobile</label>
                                        <textarea name="donation_info" class="form-control" rows="2">{{ $settings['donation_info'] }}</textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-brand">
                                            <i class="bi bi-save me-1"></i> Simpan Merchant & QRIS
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Update Donation Stats -->
                        <div class="card-clean p-4">
                            <h5 class="fw-bold mb-3" style="color: #0f172a;">
                                <i class="bi bi-bar-chart-fill text-emerald-600 me-2"></i> Perbarui Statistik Sedekah Komunitas
                            </h5>
                            <form action="{{ route('ingetin.stats.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Total Donatur</label>
                                        <input type="number" name="total_donors" class="form-control" value="{{ $stats->total_donors }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total Dana Teks (cth: 47.2jt)</label>
                                        <input type="text" name="total_amount_text" class="form-control" value="{{ $stats->total_amount_text }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Program Aktif</label>
                                        <input type="number" name="total_programs" class="form-control" value="{{ $stats->total_programs }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Catatan Transparansi Donasi</label>
                                        <input type="text" name="note" class="form-control" value="{{ $stats->note }}">
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-brand">
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
                <div class="card-clean p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold m-0" style="color: #0f172a;">Koleksi Dzikir & Doa Harian</h5>
                            <small class="text-muted">Data ini disinkronkan langsung ke aplikasi Android Ingetin.</small>
                        </div>
                        <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalAddDzikir">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Dzikir Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-clean">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Kategori</th>
                                    <th>Judul & Teks Arab</th>
                                    <th style="width: 130px;">Target</th>
                                    <th>Sumber Riwayat</th>
                                    <th style="width: 110px;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allDzikir as $dzikir)
                                    <tr>
                                        <td>
                                            <span class="badge-cat badge-{{ $dzikir->category }}">{{ $dzikir->category }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold mb-1" style="color: #0f172a; font-size: 0.95rem;">{{ $dzikir->title }}</div>
                                            <div class="arabic-text mb-2">{{ $dzikir->arabic }}</div>
                                            <div class="small fst-italic text-secondary mb-1">{{ $dzikir->latin }}</div>
                                            <div class="small text-muted">{{ $dzikir->translation }}</div>
                                            @if($dzikir->benefit)
                                                <div class="text-xs mt-1.5" style="color: #059669; font-size: 0.78rem;">
                                                    <i class="bi bi-star-fill me-1"></i> <strong>Keutamaan:</strong> {{ $dzikir->benefit }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace">
                                                {{ $dzikir->target_count }}x bacaan
                                            </span>
                                        </td>
                                        <td class="small text-secondary">{{ $dzikir->source ?? '-' }}</td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1.5">
                                                <button class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" 
                                                        onclick="editDzikir({{ json_encode($dzikir) }})" 
                                                        title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('ingetin.dzikir.delete', $dzikir->id) }}" method="POST" onsubmit="return confirm('Hapus dzikir ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2.5 py-1" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data dzikir.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: HADITS HARIAN -->
            <div class="tab-pane fade" id="tab-hadith" role="tabpanel">
                <div class="card-clean p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                        <div>
                            <h5 class="fw-bold m-0" style="color: #0f172a;">Daftar Hadits Harian</h5>
                            <small class="text-muted">Aplikasi mobile mengambil hadits pilihan bergilir secara harian.</small>
                        </div>
                        <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalAddHadith">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Hadits Baru
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-clean">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Isi Matan / Pesan Hadits</th>
                                    <th style="width: 200px;">Perawi</th>
                                    <th style="width: 180px;">Topik</th>
                                    <th style="width: 110px;" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allHadith as $hadith)
                                    <tr>
                                        <td class="text-muted font-monospace">#{{ $hadith->id }}</td>
                                        <td>
                                            <div class="text-dark fs-6" style="line-height: 1.6;">"{{ $hadith->text }}"</div>
                                        </td>
                                        <td class="fw-semibold text-emerald-700" style="color: #047857;">{{ $hadith->narrator }}</td>
                                        <td>
                                            <span class="badge bg-light text-secondary border px-2.5 py-1">
                                                {{ $hadith->topic ?? 'Umum' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1.5">
                                                <button class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" 
                                                        onclick="editHadith({{ json_encode($hadith) }})" 
                                                        title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('ingetin.hadith.delete', $hadith->id) }}" method="POST" onsubmit="return confirm('Hapus hadits ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2.5 py-1" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Belum ada data hadits.</td>
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
                        <div class="card-clean p-4 mb-4">
                            <h5 class="fw-bold mb-3" style="color: #0f172a;">
                                <i class="bi bi-hdd-network text-emerald-600 me-2"></i> Endpoint REST API Siap Hosting
                            </h5>
                            <p class="small text-muted mb-3">
                                Endpoint ini menyajikan format JSON murni dengan CORS aktif, kompatibel 100% dengan aplikasi Android Ingetin dan sistem lain.
                            </p>

                            <!-- Endpoint 1: Settings -->
                            <div class="api-pill mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success px-2 py-1">GET</span>
                                        <code class="text-dark fw-bold">/ingetin/api/settings</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/settings') }}" target="_blank" class="btn btn-sm btn-brand-light">
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
                                        <code class="text-dark fw-bold">/ingetin/api/dzikir?category=pagi</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/dzikir?category=pagi') }}" target="_blank" class="btn btn-sm btn-brand-light">
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
                                        <code class="text-dark fw-bold">/ingetin/api/hadith</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/hadith') }}" target="_blank" class="btn btn-sm btn-brand-light">
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
                                        <code class="text-dark fw-bold">/ingetin/api/prayers?lat=-6.2088&lng=106.8456</code>
                                    </div>
                                    <a href="{{ url('/ingetin/api/prayers?lat=-6.2088&lng=106.8456') }}" target="_blank" class="btn btn-sm btn-brand-light">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka API
                                    </a>
                                </div>
                                <small class="text-muted">Perhitungan hisab sholat 5 waktu & waktu Dhuha astronomis standar Kemenag RI.</small>
                            </div>
                        </div>

                        <!-- Hosting Guide Note -->
                        <div class="card-clean p-4">
                            <h6 class="fw-bold mb-2" style="color: #0f172a;">
                                <i class="bi bi-globe text-emerald-600 me-2"></i> Panduan Hosting ke Server Online
                            </h6>
                            <p class="small text-muted mb-2">
                                Ketika Anda meng-upload project SekolahKu ke VPS atau cPanel:
                            </p>
                            <ol class="small text-muted ps-3 mb-0">
                                <li class="mb-1">URL API otomatis: <code>https://domainanda.com/ingetin/api/</code></li>
                                <li class="mb-1">Admin Panel: <code>https://domainanda.com/ingetin/login</code></li>
                                <li>Buka aplikasi Ingetin di Android &rarr; Menu <strong>Profil & Pengaturan</strong> &rarr; masukkan URL hosting tersebut.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Right: Prayer Times & Admin PIN -->
                    <div class="col-lg-5">
                        <div class="card-clean p-4 mb-4">
                            <h6 class="fw-bold mb-3" style="color: #0f172a;">
                                <i class="bi bi-clock-fill text-emerald-600 me-2"></i> Jadwal Sholat Dhuha Default
                            </h6>
                            <form action="{{ route('ingetin.settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Mulai Dhuha</label>
                                        <input type="text" name="dhuha_start_time" class="form-control" value="{{ $settings['dhuha_start_time'] }}" placeholder="07:00">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Selesai Dhuha</label>
                                        <input type="text" name="dhuha_end_time" class="form-control" value="{{ $settings['dhuha_end_time'] }}" placeholder="11:00">
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-sm btn-brand">Simpan Jadwal</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-clean p-4">
                            <h6 class="fw-bold mb-3" style="color: #0f172a;">
                                <i class="bi bi-shield-lock-fill text-emerald-600 me-2"></i> Ganti PIN Admin Ingetin
                            </h6>
                            <form action="{{ route('ingetin.pin.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">PIN Baru (Minimal 4 Angka)</label>
                                    <input type="password" name="new_pin" class="form-control" placeholder="••••••" required maxlength="8">
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-brand">Ubah PIN</button>
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
                        <h5 class="modal-title fw-bold" id="dzikirModalTitle" style="color: #0f172a;">Tambah Dzikir Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="category" id="dzikir_category" class="form-select" required>
                                    <option value="pagi">Dzikir Pagi</option>
                                    <option value="petang">Dzikir Petang</option>
                                    <option value="tidur">Sebelum Tidur</option>
                                    <option value="lainnya">Lainnya / Sunnah</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Target Hitungan Bacaan</label>
                                <input type="number" name="target_count" id="dzikir_target" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Judul Amalan / Doa</label>
                                <input type="text" name="title" id="dzikir_title" class="form-control" placeholder="Contoh: Membaca Ayat Kursi" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Teks Arab (RTL)</label>
                                <textarea name="arabic" id="dzikir_arabic" class="form-control arabic-text" rows="3" required placeholder="اللَّهُ لَا إِلَٰهَ إِلَّا هُوَ..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Transliterasi Latin</label>
                                <textarea name="latin" id="dzikir_latin" class="form-control" rows="2" placeholder="Allahu laa ilaaha illaa huwal hayyul qayyuum..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Terjemahan Bahasa Indonesia</label>
                                <textarea name="translation" id="dzikir_translation" class="form-control" rows="3" placeholder="Allah, tidak ada tuhan selain Dia..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Keutamaan / Manfaat Amalan</label>
                                <input type="text" name="benefit" id="dzikir_benefit" class="form-control" placeholder="Cth: Dilindungi dari gangguan jin hingga petang">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sumber Riwayat Hadits</label>
                                <input type="text" name="source" id="dzikir_source" class="form-control" placeholder="Cth: HR. An-Nasa'i & Al-Hakim">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Urutan Tampil (Index)</label>
                                <input type="number" name="order_index" id="dzikir_order" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="dzikir_active" value="1" checked>
                                    <label class="form-check-label small" for="dzikir_active">
                                        Aktifkan di Aplikasi Mobile
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-brand">Simpan Dzikir</button>
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
                        <h5 class="modal-title fw-bold" id="hadithModalTitle" style="color: #0f172a;">Tambah Hadits Harian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Isi Matan / Pesan Hadits</label>
                            <textarea name="text" id="hadith_text" class="form-control" rows="4" required placeholder="Sesungguhnya amalan yang paling dicintai oleh Allah..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Perawi / Riwayat</label>
                            <input type="text" name="narrator" id="hadith_narrator" class="form-control" placeholder="HR. Bukhari & Muslim" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Topik / Tema Hadits</label>
                            <input type="text" name="topic" id="hadith_topic" class="form-control" placeholder="Konsistensi Ibadah">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="hadith_active" value="1" checked>
                            <label class="form-check-label small" for="hadith_active">
                                Aktifkan di Rotasi Harian
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-brand">Simpan Hadits</button>
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
