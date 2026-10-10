@extends('layouts.app')

@section('title', 'Pembukuan Revitalisasi - SekolahKu')
@section('page_title', 'Pembukuan Revitalisasi')

@section('content')
    <!-- Header Informasi Proyek & Pemilihan Periode -->
    <div class="card-custom p-3 p-sm-4 mb-4 border-top border-4 border-primary shadow-xs bg-white">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center gap-3 mb-3 pb-3 border-bottom">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1.5 flex-wrap">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-2 fw-bold" style="font-size: 0.78rem;">
                        <i class="bi bi-bank2 me-1"></i> {{ $proyek->nama_bank ?: 'Bank Kas Revitalisasi' }} ({{ $proyek->nomor_rekening ?: 'Rek. Operasional' }})
                    </span>
                    <span class="badge bg-light text-dark border px-2.5 py-1 rounded-2 fw-semibold" style="font-size: 0.78rem;">
                        <i class="bi bi-calendar-event me-1 text-primary"></i> Tahun Anggaran {{ $proyek->tahun_anggaran }}
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-2" style="font-size: 0.75rem;">
                        <i class="bi bi-shield-check me-1"></i> Status: {{ $proyek->status }}
                    </span>
                    @if($proyek->tanggal_tutup_buku)
                        <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-2" style="font-size: 0.75rem;">
                            <i class="bi bi-clock-history me-1"></i> Tutup Buku: {{ $proyek->tanggal_tutup_buku->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
                <h4 class="fw-bold text-dark m-0">{{ $proyek->nama_kegiatan }}</h4>
                <p class="text-muted small mb-0 mt-1">
                    Format Pembukuan Resmi Bantuan Pemerintah: <strong>Buku Kas Umum (BKU)</strong>, <strong>Buku Pembantu Kas Tunai (BPKT)</strong>, dan <strong>Buku Bank (BB)</strong>.
                </p>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                <!-- Dropdown Pilih Proyek -->
                <form action="{{ route('revitalisasi.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <select name="proyek_id" class="form-select form-select-sm bg-white border fw-semibold" onchange="this.form.submit()" style="min-width: 220px; min-height: 38px;">
                        @foreach($allProyeks as $p)
                            <option value="{{ $p->id }}" {{ $proyek->id == $p->id ? 'selected' : '' }}>
                                {{ $p->tahun_anggaran }} - {{ Str::limit($p->nama_kegiatan, 28) }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <button class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold shadow-2xs d-flex align-items-center justify-content-center gap-1.5" data-bs-toggle="modal" data-bs-target="#editProyekModal" style="min-height: 38px;">
                    <i class="bi bi-sliders"></i> Info Proyek & TTD
                </button>

                <button class="btn btn-primary btn-sm rounded-3 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-1.5" data-bs-toggle="modal" data-bs-target="#createTransaksiModal" style="min-height: 38px;">
                    <i class="bi bi-plus-circle-fill"></i> Catat Transaksi
                </button>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="row g-3 align-items-stretch">
            <!-- 1. Pagu & Terima Dana -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="p-3 bg-light-subtle border rounded-3 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Total Dana Masuk (Termin)</span>
                            <div class="p-1.5 bg-primary-subtle text-primary rounded-2 flex-shrink-0">
                                <i class="bi bi-box-arrow-in-down fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-primary m-0" style="font-size: 1.45rem;">Rp {{ number_format($summary->total_terima_dana, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex justify-content-between align-items-center" style="font-size: 0.76rem;">
                        <span>Pagu: Rp {{ number_format($proyek->pagu_anggaran, 0, ',', '.') }}</span>
                        <span class="badge bg-light text-dark border">Termin Cair</span>
                    </div>
                </div>
            </div>

            <!-- 2. Realisasi Pengeluaran BKU -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="p-3 bg-light-subtle border rounded-3 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Total Pengeluaran BKU</span>
                            <div class="p-1.5 bg-danger-subtle text-danger rounded-2 flex-shrink-0">
                                <i class="bi bi-cash-coin fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-danger m-0" style="font-size: 1.45rem;">Rp {{ number_format($summary->total_pengeluaran_bku, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex justify-content-between align-items-center" style="font-size: 0.76rem;">
                        @php
                            $persenSerapan = $summary->total_terima_dana > 0 ? round(($summary->total_pengeluaran_bku / $summary->total_terima_dana) * 100, 2) : 0;
                        @endphp
                        <span>Serapan Kas: <strong class="text-dark">{{ $persenSerapan }}%</strong></span>
                        <span class="badge bg-light text-secondary border">Tunai + Transfer</span>
                    </div>
                </div>
            </div>

            <!-- 3. Saldo Kas Bank (BB) -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="p-3 bg-light-subtle border rounded-3 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Saldo Kas Bank (BB)</span>
                            <div class="p-1.5 bg-info-subtle text-info-emphasis rounded-2 flex-shrink-0">
                                <i class="bi bi-credit-card-2-front fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-info-emphasis m-0" style="font-size: 1.45rem;">Rp {{ number_format($summary->saldo_bank, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex justify-content-between align-items-center" style="font-size: 0.76rem;">
                        <span>Rekening Bank</span>
                        <a href="{{ route('revitalisasi.bb', $proyek) }}" class="text-decoration-none fw-semibold text-primary">Lihat BB <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- 4. Saldo Kas Tunai (BPKT) -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
                <div class="p-3 bg-light-subtle border rounded-3 w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Saldo Kas Tunai (BPKT)</span>
                            <div class="p-1.5 bg-warning-subtle text-warning-emphasis rounded-2 flex-shrink-0">
                                <i class="bi bi-wallet2 fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-warning-emphasis m-0" style="font-size: 1.45rem;">Rp {{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex justify-content-between align-items-center" style="font-size: 0.76rem;">
                        <span>Uang Tunai di Brankas</span>
                        <a href="{{ route('revitalisasi.bpkt', $proyek) }}" class="text-decoration-none fw-semibold text-primary">Lihat BPKT <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekonsiliasi & Keseimbangan Kas Banner -->
        <div class="mt-3 p-3 rounded-3 border {{ $summary->is_balanced ? 'bg-success-subtle border-success-subtle text-dark' : 'bg-danger-subtle border-danger-subtle text-dark' }}">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">
                <div class="d-flex align-items-center gap-2.5">
                    <i class="bi {{ $summary->is_balanced ? 'bi-check-circle-fill text-success fs-4' : 'bi-exclamation-triangle-fill text-danger fs-4' }}"></i>
                    <div>
                        <div class="fw-bold" style="font-size: 0.95rem;">
                            Rekonsiliasi Saldo Kas Umum (BKU): Rp {{ number_format($summary->saldo_bku, 0, ',', '.') }}
                            @if($summary->is_balanced)
                                <span class="badge bg-success text-white ms-1 px-2 py-0.5 rounded-pill"><i class="bi bi-check2"></i> SEIMBANG (MATCH)</span>
                            @else
                                <span class="badge bg-danger text-white ms-1 px-2 py-0.5 rounded-pill"><i class="bi bi-x-circle"></i> TIDAK SEIMBANG</span>
                            @endif
                        </div>
                        <div class="small text-muted">
                            Formula Pembukuan: <strong>Saldo BKU (Rp {{ number_format($summary->saldo_bku, 0, ',', '.') }})</strong> = Saldo Bank (Rp {{ number_format($summary->saldo_bank, 0, ',', '.') }}) + Saldo Kas Tunai (Rp {{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}).
                        </div>
                    </div>
                </div>

                <!-- Breakdown Kategori Pengeluaran -->
                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                    <span class="badge bg-white text-dark border px-2 py-1 shadow-2xs" title="Bahan Bangunan">
                        Bahan: <strong>Rp {{ number_format($summary->rincian_biaya['Bahan'] ?? 0, 0, ',', '.') }}</strong>
                    </span>
                    <span class="badge bg-white text-dark border px-2 py-1 shadow-2xs" title="Peralatan">
                        Alat: <strong>Rp {{ number_format($summary->rincian_biaya['Alat'] ?? 0, 0, ',', '.') }}</strong>
                    </span>
                    <span class="badge bg-white text-dark border px-2 py-1 shadow-2xs" title="Upah Tukang">
                        Upah: <strong>Rp {{ number_format($summary->rincian_biaya['Upah'] ?? 0, 0, ',', '.') }}</strong>
                    </span>
                    <span class="badge bg-white text-dark border px-2 py-1 shadow-2xs" title="Manajemen & Honor">
                        Manajemen: <strong>Rp {{ number_format($summary->rincian_biaya['Manajemen'] ?? 0, 0, ',', '.') }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Bar to 3 Official Ledgers & Export Buttons -->
    <div class="card-custom p-3 mb-4 bg-white shadow-xs">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-stretch align-items-xl-center gap-2.5">
            <!-- Navigasi Tiga Buku -->
            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                <a href="{{ route('revitalisasi.bku', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold px-3 py-2">
                    <i class="bi bi-journal-text me-1"></i> 1. Buku Kas Umum (BKU)
                </a>
                <a href="{{ route('revitalisasi.bpkt', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold px-3 py-2">
                    <i class="bi bi-wallet2 me-1"></i> 2. Buku Kas Tunai (BPKT)
                </a>
                <a href="{{ route('revitalisasi.bb', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold px-3 py-2">
                    <i class="bi bi-bank me-1"></i> 3. Buku Bank (BB)
                </a>
                <button type="button" class="btn btn-light border btn-sm rounded-3 fw-semibold px-3 py-2" data-bs-toggle="modal" data-bs-target="#kalkulatorPajakModal">
                    <i class="bi bi-calculator me-1 text-primary"></i> Kalkulator Pajak (PPN/PPh)
                </button>
            </div>

            <!-- Export & Action Buttons -->
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <!-- Seed Sample Button -->
                <form action="{{ route('revitalisasi.proyek.seed', $proyek) }}" method="POST" onsubmit="return confirm('Muat data simulasi resmi dari contoh 05a_Simulasi Pembukuan.xlsx? Data transaksi yang ada saat ini akan digantikan dengan data simulasi.')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3 fw-semibold shadow-2xs" title="Muat data contoh 7 transaksi simulasi seperti Excel 05a">
                        <i class="bi bi-magic text-warning me-1"></i> Muat Data Simulasi Excel
                    </button>
                </form>

                <!-- Export Excel Button -->
                <a href="{{ route('revitalisasi.export-excel', $proyek) }}" class="btn btn-sm btn-success rounded-3 fw-bold shadow-xs px-3">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel (.xlsx)
                </a>

                <!-- Export PDF Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-danger rounded-3 fw-bold shadow-xs dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('revitalisasi.export-pdf', [$proyek, 'type' => 'bku']) }}"><i class="bi bi-file-text me-2 text-primary"></i> Buku Kas Umum (BKU)</a></li>
                        <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('revitalisasi.export-pdf', [$proyek, 'type' => 'bpkt']) }}"><i class="bi bi-wallet2 me-2 text-warning"></i> Buku Kas Tunai (BPKT)</a></li>
                        <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('revitalisasi.export-pdf', [$proyek, 'type' => 'bb']) }}"><i class="bi bi-bank me-2 text-info"></i> Buku Bank (BB)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Semua Transaksi Proyek -->
    <div class="card-custom p-0 bg-white shadow-xs overflow-hidden mb-4">
        <!-- Filter Header -->
        <div class="p-3 border-bottom bg-slate-50">
            <form action="{{ route('revitalisasi.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="proyek_id" value="{{ $proyek->id }}">

                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari uraian, no bukti, vendor..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <select name="jenis_transaksi" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Jenis --</option>
                        <option value="terima_dana" {{ request('jenis_transaksi') == 'terima_dana' ? 'selected' : '' }}>Penerimaan Dana</option>
                        <option value="tarik_bank" {{ request('jenis_transaksi') == 'tarik_bank' ? 'selected' : '' }}>Penarikan Bank (UP)</option>
                        <option value="belanja_tunai" {{ request('jenis_transaksi') == 'belanja_tunai' ? 'selected' : '' }}>Belanja Tunai</option>
                        <option value="belanja_transfer" {{ request('jenis_transaksi') == 'belanja_transfer' ? 'selected' : '' }}>Belanja Transfer</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="jenis_biaya" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        <option value="Bahan" {{ request('jenis_biaya') == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                        <option value="Alat" {{ request('jenis_biaya') == 'Alat' ? 'selected' : '' }}>Alat</option>
                        <option value="Upah" {{ request('jenis_biaya') == 'Upah' ? 'selected' : '' }}>Upah</option>
                        <option value="Manajemen" {{ request('jenis_biaya') == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Tanggal Mulai">
                </div>

                <div class="col-6 col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Tanggal Akhir">
                </div>

                <div class="col-12 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100" title="Filter"><i class="bi bi-filter"></i></button>
                    @if(request()->hasAny(['search', 'jenis_transaksi', 'jenis_biaya', 'date_from', 'date_to']))
                        <a href="{{ route('revitalisasi.index', ['proyek_id' => $proyek->id]) }}" class="btn btn-sm btn-light border text-danger" title="Reset"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;">
                <thead class="bg-light text-secondary text-uppercase fw-bold" style="font-size: 0.74rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="py-3 px-3 text-center" style="width: 70px;">No Bukti</th>
                        <th class="py-3 px-2" style="width: 105px;">Tanggal</th>
                        <th class="py-3 px-3">Uraian Transaksi & Rincian</th>
                        <th class="py-3 px-2 text-center" style="width: 110px;">Jenis Biaya</th>
                        <th class="py-3 px-2" style="width: 140px;">Penerima / Vendor</th>
                        <th class="py-3 px-3 text-end" style="width: 140px;">Nominal (Rp)</th>
                        <th class="py-3 px-2 text-center" style="width: 140px;">Pencatatan Buku</th>
                        <th class="py-3 px-3 text-center" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                        <tr>
                            <td class="px-3 text-center fw-bold text-primary">
                                <span class="badge bg-light text-primary border px-2 py-1 rounded-2">
                                    {{ $trx->nomor_bukti }}
                                </span>
                            </td>
                            <td class="px-2 text-nowrap">
                                {{ $trx->tanggal ? $trx->tanggal->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-3">
                                <div class="fw-bold text-dark">{{ $trx->uraian }}</div>
                                @if($trx->items->isNotEmpty())
                                    <div class="mt-1 ps-2 border-start border-2 border-primary-subtle">
                                        @foreach($trx->items as $item)
                                            <div class="small text-muted d-flex justify-content-between gap-2 py-0.5">
                                                <span>• {{ $item->uraian_item }}</span>
                                                <span class="text-nowrap fw-semibold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if($trx->keterangan)
                                    <div class="text-muted text-xs mt-0.5" style="font-size: 0.75rem;">
                                        <i class="bi bi-info-circle me-1"></i>{{ $trx->keterangan }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-2 text-center">
                                @if($trx->jenis_biaya)
                                    @php
                                        $badgeBiaya = match($trx->jenis_biaya) {
                                            'Bahan' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
                                            'Alat' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                            'Upah' => 'bg-primary-subtle text-primary border border-primary-subtle',
                                            'Manajemen' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                            default => 'bg-light text-dark border',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeBiaya }} px-2 py-1 rounded-2">
                                        {{ $trx->jenis_biaya }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="px-2 small text-dark">
                                {{ $trx->penerima_vendor ?: '-' }}
                            </td>
                            <td class="px-3 text-end fw-bold {{ $trx->jenis_transaksi === 'terima_dana' ? 'text-success' : 'text-dark' }}">
                                {{ $trx->jenis_transaksi === 'terima_dana' ? '+' : '' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-2 text-center">
                                @php $buku = $trx->pencatatan_buku_info; @endphp
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    @if($buku['bb'] !== '-')
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle py-0.5 px-1.5" title="Buku Bank: {{ $buku['bb'] }}">BB: {{ $buku['bb'] }}</span>
                                    @endif
                                    @if($buku['bpkt'] !== '-')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-0.5 px-1.5" title="Buku Kas Tunai: {{ $buku['bpkt'] }}">BPKT: {{ $buku['bpkt'] }}</span>
                                    @endif
                                    @if($buku['bku'] !== '-')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-0.5 px-1.5" title="Buku Kas Umum: {{ $buku['bku'] }}">BKU: {{ $buku['bku'] }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    @if(in_array($trx->jenis_transaksi, ['belanja_tunai', 'belanja_transfer']))
                                        <a href="{{ route('revitalisasi.kuitansi', $trx) }}" target="_blank" class="btn btn-sm btn-light border text-primary px-1.5 py-1" title="Cetak Kuitansi Resmi">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                    @endif

                                    <button class="btn btn-sm btn-light border text-secondary px-1.5 py-1" title="Edit Transaksi" onclick='editTransaksi(@json($trx))'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('revitalisasi.transaksi.destroy', $trx) }}" method="POST" onsubmit="return confirm('Hapus transaksi nomor bukti {{ $trx->nomor_bukti }}?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger px-1.5 py-1" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Belum ada transaksi pada pembukuan proyek ini</h6>
                                <p class="small text-muted mb-3">Klik tombol "Catat Transaksi" untuk mulai mencatat atau "Muat Data Simulasi" untuk mencoba data contoh.</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-primary btn-sm rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#createTransaksiModal">
                                        <i class="bi bi-plus-circle me-1"></i> Catat Transaksi Pertama
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksis->hasPages())
            <div class="p-3 border-top bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $transaksis->firstItem() }} hingga {{ $transaksis->lastItem() }} dari {{ $transaksis->total() }} transaksi
                </div>
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Catat / Tambah Transaksi -->
    <div class="modal fade" id="createTransaksiModal" tabindex="-1" aria-labelledby="createTransaksiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('revitalisasi.transaksi.store', $proyek) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold" id="createTransaksiModalLabel">
                            <i class="bi bi-plus-circle-fill text-primary me-1.5"></i> Catat Transaksi Pembukuan Revitalisasi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Petunjuk Langkah Pencatatan Otomatis -->
                        <div class="alert alert-info py-2.5 px-3 rounded-3 small mb-3">
                            <i class="bi bi-info-circle-fill me-1 text-primary"></i>
                            Sistem secara otomatis akan mendistribusikan pos transaksi ke <strong>Buku Bank (BB)</strong>, <strong>Buku Kas Tunai (BPKT)</strong>, dan <strong>Buku Kas Umum (BKU)</strong> sesuai standar akuntansi bantuan pemerintah.
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Nomor Bukti <span class="text-danger">*</span></label>
                                @php
                                    $nextNo = ($proyek->transaksis()->max('id') ?? 0) + 1;
                                @endphp
                                <input type="text" name="nomor_bukti" class="form-control" required placeholder="Contoh: 1, 2, 3..." value="{{ old('nomor_bukti', $nextNo) }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" required value="{{ old('tanggal', date('Y-m-d')) }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Jenis Transaksi <span class="text-danger">*</span></label>
                                <select name="jenis_transaksi" class="form-select fw-semibold" id="create_jenis_transaksi" required onchange="toggleJenisBiaya(this, 'create')">
                                    <option value="belanja_tunai" selected>1. Belanja Tunai (dari Kas Tunai)</option>
                                    <option value="belanja_transfer">2. Belanja Transfer Bank (Non-Tunai)</option>
                                    <option value="terima_dana">3. Penerimaan Dana (Termin dari Direktorat)</option>
                                    <option value="tarik_bank">4. Penarikan Tunai Bank ke Kas (UP)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Uraian Transaksi <span class="text-danger">*</span></label>
                                <input type="text" name="uraian" class="form-control" required placeholder="Contoh: Belanja material di TB ABC / Pembayaran Upah / Terima termin 1..." value="{{ old('uraian') }}">
                            </div>

                            <div class="col-12 col-md-6" id="create_box_jenis_biaya">
                                <label class="form-label small fw-bold">Jenis / Kategori Biaya</label>
                                <select name="jenis_biaya" class="form-select" id="create_jenis_biaya">
                                    <option value="Bahan">Bahan (Material Bangunan / Komponen)</option>
                                    <option value="Alat">Alat (Peralatan Kerja / Sewa Mesin)</option>
                                    <option value="Upah">Upah (Tukang / Mandor / Pekerja)</option>
                                    <option value="Manajemen">Manajemen (Konsumsi, Rapat, ATK, Honor)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Penerima / Toko / Vendor</label>
                                <input type="text" name="penerima_vendor" class="form-control" placeholder="Contoh: Toko Bangunan ABC, CV DEF..." value="{{ old('penerima_vendor') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Nominal Total (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="number" name="nominal" id="create_nominal" class="form-control fw-bold" required min="0" step="any" placeholder="0" value="{{ old('nominal') }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Upload Foto Struk / Nota Bukti (Opsional)</label>
                                <input type="file" name="file_bukti" class="form-control" accept="image/*,application/pdf">
                            </div>

                            <!-- Bagian Multi-Item Sub Belanja Material (seperti TB ABC di Excel) -->
                            <div class="col-12 mt-3" id="create_subitems_section">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-bold small text-dark"><i class="bi bi-list-check text-primary me-1"></i> Rincian Sub-Item / Breakdown Nota (Opsional)</span>
                                            <div class="text-muted text-xs" style="font-size: 0.74rem;">Gunakan jika 1 nota memiliki rincian item (contoh: semen, pasir, bata, gerobak).</div>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-outline-primary fw-bold" onclick="addSubItemRow('create')">
                                            <i class="bi bi-plus"></i> Tambah Baris Item
                                        </button>
                                    </div>

                                    <div id="create_items_container" class="d-flex flex-column gap-2">
                                        <!-- Dynamic item rows will appear here -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Keterangan Tambahan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan opsional mengenai transaksi...">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="bi bi-save me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div class="modal fade" id="editTransaksiModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="editTransaksiForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-pencil-square text-primary me-1.5"></i> Edit Transaksi Pembukuan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Nomor Bukti <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_bukti" id="edit_nomor_bukti" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Jenis Transaksi <span class="text-danger">*</span></label>
                                <select name="jenis_transaksi" class="form-select fw-semibold" id="edit_jenis_transaksi" required onchange="toggleJenisBiaya(this, 'edit')">
                                    <option value="belanja_tunai">1. Belanja Tunai (dari Kas Tunai)</option>
                                    <option value="belanja_transfer">2. Belanja Transfer Bank (Non-Tunai)</option>
                                    <option value="terima_dana">3. Penerimaan Dana (Termin dari Direktorat)</option>
                                    <option value="tarik_bank">4. Penarikan Tunai Bank ke Kas (UP)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Uraian Transaksi <span class="text-danger">*</span></label>
                                <input type="text" name="uraian" id="edit_uraian" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-6" id="edit_box_jenis_biaya">
                                <label class="form-label small fw-bold">Jenis / Kategori Biaya</label>
                                <select name="jenis_biaya" class="form-select" id="edit_jenis_biaya">
                                    <option value="Bahan">Bahan (Material Bangunan / Komponen)</option>
                                    <option value="Alat">Alat (Peralatan Kerja / Sewa Mesin)</option>
                                    <option value="Upah">Upah (Tukang / Mandor / Pekerja)</option>
                                    <option value="Manajemen">Manajemen (Konsumsi, Rapat, ATK, Honor)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Penerima / Toko / Vendor</label>
                                <input type="text" name="penerima_vendor" id="edit_penerima_vendor" class="form-control">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Nominal Total (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="number" name="nominal" id="edit_nominal" class="form-control fw-bold" required min="0" step="any">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Ganti Foto Struk / Nota Bukti</label>
                                <input type="file" name="file_bukti" class="form-control" accept="image/*,application/pdf">
                            </div>

                            <!-- Bagian Multi-Item Sub Belanja Material -->
                            <div class="col-12 mt-3" id="edit_subitems_section">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-bold small text-dark"><i class="bi bi-list-check text-primary me-1"></i> Rincian Sub-Item / Breakdown Nota</span>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-outline-primary fw-bold" onclick="addSubItemRow('edit')">
                                            <i class="bi bi-plus"></i> Tambah Baris Item
                                        </button>
                                    </div>

                                    <div id="edit_items_container" class="d-flex flex-column gap-2">
                                        <!-- Dynamic item rows will appear here -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Keterangan Tambahan</label>
                                <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="bi bi-save me-1"></i> Perbarui Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pengaturan Proyek & 4 Pejabat Penandatangan -->
    <div class="modal fade" id="editProyekModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('revitalisasi.proyek.update', $proyek) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-gear-fill text-primary me-1.5"></i> Informasi Proyek Revitalisasi & Pejabat Penandatangan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h6 class="fw-bold text-primary mb-2 border-bottom pb-1">1. Informasi Kegiatan & Rekening Bank</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-8">
                                <label class="form-label small fw-bold">Nama Kegiatan Revitalisasi <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control" required value="{{ old('nama_kegiatan', $proyek->nama_kegiatan) }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Tahun Anggaran <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_anggaran" class="form-control" required min="2020" max="2035" value="{{ old('tahun_anggaran', $proyek->tahun_anggaran) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Pagu Anggaran Total (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">Rp</span>
                                    <input type="number" name="pagu_anggaran" class="form-control fw-bold" required min="0" step="any" value="{{ old('pagu_anggaran', (float) $proyek->pagu_anggaran) }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Nama Bank Penampung</label>
                                <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: Bank Mandiri / BRI" value="{{ old('nama_bank', $proyek->nama_bank) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Nomor Rekening Bank</label>
                                <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening', $proyek->nomor_rekening) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Atas Nama Rekening</label>
                                <input type="text" name="atas_nama_rekening" class="form-control" value="{{ old('atas_nama_rekening', $proyek->atas_nama_rekening) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Lokasi Penerbitan Laporan</label>
                                <input type="text" name="lokasi_surat" class="form-control" value="{{ old('lokasi_surat', $proyek->lokasi_surat) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Tanggal Tutup Buku Kas</label>
                                <input type="date" name="tanggal_tutup_buku" class="form-control" value="{{ old('tanggal_tutup_buku', $proyek->tanggal_tutup_buku ? $proyek->tanggal_tutup_buku->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <h6 class="fw-bold text-primary mb-2 border-bottom pb-1">2. Empat (4) Pejabat Penandatangan Laporan</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">1. Kepala Sekolah (Penanggung Jawab)</label>
                                <input type="text" name="penanggung_jawab_nama" class="form-control mb-1.5" placeholder="Nama Lengkap & Gelar" value="{{ old('penanggung_jawab_nama', $proyek->penanggung_jawab_nama) }}">
                                <input type="text" name="penanggung_jawab_nip" class="form-control" placeholder="NIP / NUPTK / -" value="{{ old('penanggung_jawab_nip', $proyek->penanggung_jawab_nip) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">2. Ketua Tim P2SP / Panitia</label>
                                <input type="text" name="ketua_p2sp_nama" class="form-control mb-1.5" placeholder="Nama Lengkap & Gelar" value="{{ old('ketua_p2sp_nama', $proyek->ketua_p2sp_nama) }}">
                                <input type="text" name="ketua_p2sp_nip" class="form-control" placeholder="NIP / -" value="{{ old('ketua_p2sp_nip', $proyek->ketua_p2sp_nip) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">3. Bendahara Kegiatan</label>
                                <input type="text" name="bendahara_nama" class="form-control mb-1.5" placeholder="Nama Lengkap & Gelar" value="{{ old('bendahara_nama', $proyek->bendahara_nama) }}">
                                <input type="text" name="bendahara_nip" class="form-control" placeholder="NIP / -" value="{{ old('bendahara_nip', $proyek->bendahara_nip) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">4. Pengawas / Tim Teknis</label>
                                <input type="text" name="pengawas_nama" class="form-control mb-1.5" placeholder="Nama Lengkap & Gelar" value="{{ old('pengawas_nama', $proyek->pengawas_nama) }}">
                                <input type="text" name="pengawas_nip" class="form-control" placeholder="NIP / -" value="{{ old('pengawas_nip', $proyek->pengawas_nip) }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kalkulator Pajak Revitalisasi -->
    <div class="modal fade" id="kalkulatorPajakModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-calculator text-primary me-1.5"></i> Kalkulator Pajak Proyek Revitalisasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Skema Perhitungan Pajak</label>
                        <select id="pajak_skema" class="form-select" onchange="hitungPajak()">
                            <option value="ppn_include">1. Belanja Barang Include PPN 11% + PPh 22 (1.5%)</option>
                            <option value="ppn_standar">2. Belanja Barang Include PPN 11% + PPh 22 (2.0%)</option>
                            <option value="pph_21">3. Jasa / Honorarium (PPh 21 5%)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Total Nilai Transaksi Bruto (Rp)</label>
                        <input type="number" id="pajak_nilai_bruto" class="form-control fw-bold" value="2500000" oninput="hitungPajak()">
                    </div>

                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">Rincian Potongan Pajak & Pembayaran:</h6>
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr>
                                <td class="text-muted">Dasar Pengenaan Pajak (DPP):</td>
                                <td class="text-end fw-bold" id="res_dpp">Rp 2.252.252</td>
                            </tr>
                            <tr>
                                <td class="text-muted">PPN (11/111 x Total):</td>
                                <td class="text-end fw-bold text-danger" id="res_ppn">Rp 247.748</td>
                            </tr>
                            <tr>
                                <td class="text-muted" id="label_pph">PPh 22 (1.5% x DPP):</td>
                                <td class="text-end fw-bold text-danger" id="res_pph">Rp 33.784</td>
                            </tr>
                            <tr class="border-top">
                                <td class="fw-bold text-dark pt-2">Nilai Bersih Diterima / Dibayar (Netto):</td>
                                <td class="text-end fw-bold text-success pt-2 fs-6" id="res_netto">Rp 2.218.468</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleJenisBiaya(selectElem, mode) {
        const val = selectElem.value;
        const boxBiaya = document.getElementById(mode + '_box_jenis_biaya');
        const subitemsSec = document.getElementById(mode + '_subitems_section');

        if (val === 'belanja_tunai' || val === 'belanja_transfer') {
            if (boxBiaya) boxBiaya.style.display = 'block';
            if (subitemsSec) subitemsSec.style.display = 'block';
        } else {
            if (boxBiaya) boxBiaya.style.display = 'none';
            if (subitemsSec) subitemsSec.style.display = 'none';
        }
    }

    let itemIndexCounter = 100;

    function addSubItemRow(mode, itemData = null) {
        const container = document.getElementById(mode + '_items_container');
        const idx = itemIndexCounter++;

        const row = document.createElement('div');
        row.className = 'row g-1.5 align-items-center bg-white p-2 rounded-2 border';
        row.id = `${mode}_item_row_${idx}`;

        const uraianVal = itemData ? itemData.uraian_item : '';
        const biayaVal = itemData ? (itemData.jenis_biaya || 'Bahan') : 'Bahan';
        const subtotalVal = itemData ? itemData.subtotal : '';

        row.innerHTML = `
            <div class="col-12 col-md-5">
                <input type="text" name="items[${idx}][uraian_item]" class="form-control form-control-sm" placeholder="Nama item (cth: semen 100sak @70.000)" value="${uraianVal}" required>
            </div>
            <div class="col-6 col-md-3">
                <select name="items[${idx}][jenis_biaya]" class="form-select form-select-sm">
                    <option value="Bahan" ${biayaVal === 'Bahan' ? 'selected' : ''}>Bahan</option>
                    <option value="Alat" ${biayaVal === 'Alat' ? 'selected' : ''}>Alat</option>
                    <option value="Upah" ${biayaVal === 'Upah' ? 'selected' : ''}>Upah</option>
                    <option value="Manajemen" ${biayaVal === 'Manajemen' ? 'selected' : ''}>Manajemen</option>
                </select>
            </div>
            <div class="col-5 col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">Rp</span>
                    <input type="number" name="items[${idx}][subtotal]" class="form-control form-control-sm item-subtotal-${mode} fw-bold" placeholder="Subtotal" value="${subtotalVal}" required oninput="recalcTotalNominal('${mode}')">
                </div>
            </div>
            <div class="col-1 col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="removeItemRow('${mode}_item_row_${idx}', '${mode}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

        container.appendChild(row);
    }

    function removeItemRow(rowId, mode) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            recalcTotalNominal(mode);
        }
    }

    function recalcTotalNominal(mode) {
        const inputs = document.querySelectorAll(`.item-subtotal-${mode}`);
        if (inputs.length > 0) {
            let total = 0;
            inputs.forEach(input => {
                const v = parseFloat(input.value) || 0;
                total += v;
            });
            const nominalInput = document.getElementById(mode + '_nominal');
            if (nominalInput && total > 0) {
                nominalInput.value = total;
            }
        }
    }

    function editTransaksi(trx) {
        const form = document.getElementById('editTransaksiForm');
        form.action = `/revitalisasi/transaksi/${trx.id}`;

        document.getElementById('edit_nomor_bukti').value = trx.nomor_bukti || '';
        document.getElementById('edit_tanggal').value = trx.tanggal ? trx.tanggal.substring(0, 10) : '';
        document.getElementById('edit_jenis_transaksi').value = trx.jenis_transaksi || 'belanja_tunai';
        document.getElementById('edit_uraian').value = trx.uraian || '';
        document.getElementById('edit_penerima_vendor').value = trx.penerima_vendor || '';
        document.getElementById('edit_nominal').value = parseFloat(trx.nominal) || 0;
        document.getElementById('edit_keterangan').value = trx.keterangan || '';

        if (trx.jenis_biaya) {
            document.getElementById('edit_jenis_biaya').value = trx.jenis_biaya;
        }

        toggleJenisBiaya(document.getElementById('edit_jenis_transaksi'), 'edit');

        // Populate sub-items
        const container = document.getElementById('edit_items_container');
        container.innerHTML = '';

        if (trx.items && trx.items.length > 0) {
            trx.items.forEach(item => {
                addSubItemRow('edit', item);
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('editTransaksiModal'));
        modal.show();
    }

    function hitungPajak() {
        const skema = document.getElementById('pajak_skema').value;
        const bruto = parseFloat(document.getElementById('pajak_nilai_bruto').value) || 0;

        let dpp = 0;
        let ppn = 0;
        let pph = 0;
        let netto = 0;

        const labelPph = document.getElementById('label_pph');

        if (skema === 'ppn_include') {
            // Include PPN 11% & PPh 22 (1.5%)
            ppn = Math.round((11 / 111) * bruto);
            dpp = bruto - ppn;
            pph = Math.round(dpp * 0.015);
            netto = dpp - pph;
            labelPph.innerText = 'PPh 22 (1.5% x DPP):';
        } else if (skema === 'ppn_standar') {
            // Include PPN 11% & PPh 22 (2.0%)
            ppn = Math.round((11 / 111) * bruto);
            dpp = bruto - ppn;
            pph = Math.round(dpp * 0.02);
            netto = dpp - pph;
            labelPph.innerText = 'PPh 22 (2% x DPP):';
        } else if (skema === 'pph_21') {
            // PPh 21 Honor (5%)
            dpp = bruto;
            ppn = 0;
            pph = Math.round(bruto * 0.05);
            netto = bruto - pph;
            labelPph.innerText = 'PPh 21 (5% x Honor):';
        }

        document.getElementById('res_dpp').innerText = 'Rp ' + dpp.toLocaleString('id-ID');
        document.getElementById('res_ppn').innerText = 'Rp ' + ppn.toLocaleString('id-ID');
        document.getElementById('res_pph').innerText = 'Rp ' + pph.toLocaleString('id-ID');
        document.getElementById('res_netto').innerText = 'Rp ' + netto.toLocaleString('id-ID');
    }

    // Initial tax calculation
    document.addEventListener('DOMContentLoaded', function() {
        hitungPajak();
    });
</script>
@endpush
