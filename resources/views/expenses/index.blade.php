@extends('layouts.app')

@section('title', 'BendaharaKu & LPJ BOSP - SekolahKu')
@section('page_title', 'BendaharaKu & LPJ BOSP')

@section('content')
    <!-- Card Informasi Dana BOSP Awal Sesuai Periode -->
    <div class="card-custom p-4 mb-4 border-top border-4 border-primary shadow-sm" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3 pb-3 border-bottom">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge bg-primary px-3 py-1 rounded-pill fw-bold" style="font-size: 0.8rem;">
                        <i class="bi bi-wallet-fill me-1"></i> {{ $danaBosp->sumber_dana ?? 'BOSP Reguler' }}
                    </span>
                    <span class="badge bg-light text-dark border px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                        <i class="bi bi-calendar3 me-1 text-primary"></i> Periode: {{ $currentPeriode }} {{ $currentYear }}
                    </span>
                    @if($danaBosp && $danaBosp->tanggal_cair)
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
                            <i class="bi bi-check-circle me-1"></i> Cair: {{ $danaBosp->tanggal_cair->format('d/m/Y') }}
                        </span>
                    @endif
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
                        <i class="bi bi-clock-history me-1"></i> {{ $startDate->translatedFormat('d M') }} - {{ $endDate->translatedFormat('d M Y') }}
                    </span>
                </div>
                <h5 class="fw-bold text-dark m-0">Anggaran & Saldo Terkini Kas Dana BOSP</h5>
                <small class="text-muted">Pantau dana pencairan awal BOSP, pengeluaran talangan yang sudah diganti (reimburse), dan saldo kas terkini sekolah.</small>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Dropdown Pilih Periode BOSP -->
                <form action="{{ route('expenses.index') }}" method="GET" class="d-flex align-items-center gap-1.5" id="bospPeriodForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('category_id')) <input type="hidden" name="category_id" value="{{ request('category_id') }}"> @endif
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif

                    <select name="bosp_year" class="form-select form-select-sm bg-white border fw-semibold" onchange="this.form.submit()" style="width: 95px;">
                        @for($y = date('Y') + 1; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <select name="bosp_periode" class="form-select form-select-sm bg-white border fw-semibold" onchange="this.form.submit()" style="min-width: 190px;">
                        <option value="Tahap 1 (Semester I)" {{ $currentPeriode == 'Tahap 1 (Semester I)' ? 'selected' : '' }}>Tahap 1 (Semester I)</option>
                        <option value="Tahap 2 (Semester II)" {{ $currentPeriode == 'Tahap 2 (Semester II)' ? 'selected' : '' }}>Tahap 2 (Semester II)</option>
                        <option value="Triwulan 1" {{ $currentPeriode == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1 (Jan - Mar)</option>
                        <option value="Triwulan 2" {{ $currentPeriode == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2 (Apr - Jun)</option>
                        <option value="Triwulan 3" {{ $currentPeriode == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3 (Jul - Sep)</option>
                        <option value="Triwulan 4" {{ $currentPeriode == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4 (Okt - Des)</option>
                        <option value="Tahunan" {{ $currentPeriode == 'Tahunan' ? 'selected' : '' }}>Tahunan (Jan - Des)</option>
                    </select>
                </form>

                <button class="btn btn-primary btn-sm rounded-3 fw-bold shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#bospModal">
                    <i class="bi bi-pencil-square"></i> {{ $danaBosp ? 'Edit Dana BOSP' : 'Input Dana BOSP Cair' }}
                </button>
            </div>
        </div>

        <!-- 3 Metric Boxes -->
        <div class="row g-3 align-items-stretch">
            <div class="col-12 col-md-4 d-flex">
                <div class="p-3.5 bg-white border rounded-4 shadow-2xs w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2" style="min-height: 38px;">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Dana BOSP Cair (Awal Periode)</span>
                            <div class="p-1.5 bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                                <i class="bi bi-box-arrow-in-down fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-primary m-0" style="font-size: 1.65rem;">Rp {{ number_format($nominalDanaBosp, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex align-items-center" style="min-height: 28px; font-size: 0.76rem;">
                        @if($danaBosp)
                            <span class="text-truncate" title="{{ $danaBosp->catatan ?: $danaBosp->sumber_dana }}"><i class="bi bi-info-circle me-1"></i>{{ $danaBosp->catatan ?: ($danaBosp->sumber_dana . ' Cair') }}</span>
                        @else
                            <span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Belum diatur untuk periode ini</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 d-flex">
                <div class="p-3.5 bg-white border rounded-4 shadow-2xs w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2" style="min-height: 38px;">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Talangan Sudah Diganti (Realisasi Kas)</span>
                            <div class="p-1.5 bg-danger-subtle text-danger rounded-3 flex-shrink-0">
                                <i class="bi bi-cash-coin fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-danger m-0" style="font-size: 1.65rem;">Rp {{ number_format($talanganDigantiPeriode, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex justify-content-between align-items-center flex-wrap gap-1" style="min-height: 28px; font-size: 0.76rem;">
                        <span>Serapan Kas Riil: <strong class="text-dark">{{ $persentaseSerapan }}%</strong></span>
                        @if($talanganPendingPeriode > 0)
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-0.5 px-1.5" title="Talangan diajukan yang belum dibayarkan">
                                Pending: Rp {{ number_format($talanganPendingPeriode, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 d-flex">
                <div class="p-3.5 bg-white border rounded-4 shadow-2xs w-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2" style="min-height: 38px;">
                            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.4px;">Saldo Terkini Kas BOSP</span>
                            <div class="p-1.5 {{ $saldoTerkiniBosp >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-3 flex-shrink-0">
                                <i class="bi bi-safe fs-6"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold {{ $saldoTerkiniBosp >= 0 ? 'text-success' : 'text-danger' }} m-0" style="font-size: 1.65rem;">
                            Rp {{ number_format($saldoTerkiniBosp, 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="pt-2 mt-2 border-top border-light-subtle small text-muted d-flex justify-content-between align-items-center flex-wrap gap-1" style="min-height: 28px; font-size: 0.76rem;">
                        @if($saldoTerkiniBosp >= 0)
                            <span class="text-success"><i class="bi bi-shield-check me-1"></i>Kas Riil Tersedia</span>
                            @if($talanganPendingPeriode > 0)
                                <span class="text-muted" title="Estimasi sisa saldo jika seluruh talangan pending diganti">(Est. Sisa: Rp {{ number_format($estimasiSisaSaldo, 0, ',', '.') }})</span>
                            @endif
                        @else
                            <span class="text-danger"><i class="bi bi-shield-exclamation me-1"></i>Defisit Kas</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar Serapan BOSP -->
        @if($nominalDanaBosp > 0)
            <div class="mt-3 pt-2">
                <div class="d-flex justify-content-between align-items-center small mb-1">
                    <span class="text-muted" style="font-size: 0.78rem;">
                        Progress Serapan Kas BOSP Periode {{ $currentPeriode }} {{ $currentYear }}:
                    </span>
                    <span class="fw-bold text-dark" style="font-size: 0.78rem;">
                        {{ $persentaseSerapan }}% Terpakai (Reimburse Selesai)
                        @if($talanganPendingPeriode > 0)
                            <span class="text-warning fw-semibold ms-1">(+{{ $persentasePending }}% Pending)</span>
                        @endif
                    </span>
                </div>
                <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e2e8f0;">
                    <div class="progress-bar bg-primary" 
                         role="progressbar" 
                         style="width: {{ $persentaseSerapan }}%; border-radius: 10px 0 0 10px;" 
                         aria-valuenow="{{ $persentaseSerapan }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"
                         title="Sudah Diganti: {{ $persentaseSerapan }}%">
                    </div>
                    @if($talanganPendingPeriode > 0)
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: {{ $persentasePending }}%;" 
                             aria-valuenow="{{ $persentasePending }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"
                             title="Pending Penggantian: {{ $persentasePending }}%">
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1 text-muted flex-wrap gap-2" style="font-size: 0.72rem;">
                    <span><i class="bi bi-dot text-primary fs-6"></i> Sudah Diganti: Rp {{ number_format($talanganDigantiPeriode, 0, ',', '.') }}</span>
                    @if($talanganPendingPeriode > 0)
                        <span><i class="bi bi-dot text-warning fs-6"></i> Belum Diganti: Rp {{ number_format($talanganPendingPeriode, 0, ',', '.') }}</span>
                    @endif
                    <span class="fw-semibold text-dark"><i class="bi bi-dot text-success fs-6"></i> Saldo Terkini: Rp {{ number_format($saldoTerkiniBosp, 0, ',', '.') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- KPI Summary Cards (Talangan Status) -->
    <div class="row g-3 mb-4 align-items-stretch">
        <div class="col-12 col-md-4 d-flex">
            <div class="card-custom p-3.5 w-100 rounded-4 shadow-sm d-flex flex-column justify-content-between" style="border: 2.5px solid #0d6efd; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.73rem; letter-spacing: 0.3px; line-height: 1.35;">Total Seluruh Talangan Dicatat</span>
                    <div class="p-1.5 bg-primary-subtle text-primary rounded-3 flex-shrink-0">
                        <i class="bi bi-receipt fs-6"></i>
                    </div>
                </div>
                <div>
                    <h4 class="fw-bold m-0 text-dark" style="font-size: 1.55rem;">Rp {{ number_format($totalTalangan, 0, ',', '.') }}</h4>
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Semua catatan transaksi pengeluaran</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex">
            <div class="card-custom p-3.5 w-100 rounded-4 shadow-sm d-flex flex-column justify-content-between" style="border: 2.5px solid #ffc107; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.73rem; letter-spacing: 0.3px; line-height: 1.35;">Belum Diganti (Menunggu Reimburse)</span>
                    <div class="p-1.5 bg-warning-subtle text-warning-emphasis rounded-3 flex-shrink-0">
                        <i class="bi bi-hourglass-split fs-6"></i>
                    </div>
                </div>
                <div>
                    <h4 class="fw-bold m-0 text-warning" style="font-size: 1.55rem;">Rp {{ number_format($totalPending, 0, ',', '.') }}</h4>
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Status Belum Diajukan / Diajukan / Disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex">
            <div class="card-custom p-3.5 w-100 rounded-4 shadow-sm d-flex flex-column justify-content-between" style="border: 2.5px solid #198754; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2" style="min-height: 38px;">
                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.73rem; letter-spacing: 0.3px; line-height: 1.35;">Sudah Diganti (Telah Direimburse)</span>
                    <div class="p-1.5 bg-success-subtle text-success rounded-3 flex-shrink-0">
                        <i class="bi bi-check-circle-fill fs-6"></i>
                    </div>
                </div>
                <div>
                    <h4 class="fw-bold m-0 text-success" style="font-size: 1.55rem;">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h4>
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Dana talangan telah dicairkan ke pengaju</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Box -->
    <div class="card-custom p-3 mb-4">
        <form action="{{ route('expenses.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0"
                        placeholder="Cari uraian, vendor, toko..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="category_id" class="form-select bg-light">
                    <option value="">Semua Kategori BOSP</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }} ({{ $cat->kode_bosp ?: 'BOSP' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select bg-light">
                    <option value="">Semua Status</option>
                    <option value="Belum Diajukan" {{ request('status') == 'Belum Diajukan' ? 'selected' : '' }}>Belum
                        Diajukan</option>
                    <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Dibayar" {{ request('status') == 'Dibayar' ? 'selected' : '' }}>Dibayar (Selesai)</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill fw-semibold">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'category_id', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('expenses.index') }}" class="btn btn-light border" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h5 class="fw-bold m-0 text-dark"><i class="bi bi-cash-stack me-2 text-primary"></i>Pencatatan Talangan
                    Pribadi BOSP</h5>
                <small class="text-muted">Kelola pengeluaran operasional talangan pribadi yang akan direimburse melalui dana
                    BOSP</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('expenses.categories.index') }}"
                    class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1">
                    <i class="bi bi-tags"></i> Kategori BOSP
                </a>
                <a href="{{ route('expenses.report') }}"
                    class="btn btn-outline-danger btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1">
                    <i class="bi bi-file-earmark-pdf"></i> Rekap Periode & Cetak LPJ
                </a>
                <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#newExpenseModal">
                    <i class="bi bi-plus-lg"></i> Input Talangan (&lt; 30 dtk)
                </button>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Tanggal</th>
                        <th>Pengaju</th>
                        <th>Kategori BOSP</th>
                        <th>Uraian Keperluan</th>
                        <th style="width: 130px;">Nominal</th>
                        <th style="width: 100px;">Bukti Nota</th>
                        <th style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                        <tr>
                            <td class="text-nowrap">{{ $e->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $e->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $e->user->roles->first()?->name ?? 'Pengurus' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $e->category->nama_kategori ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $e->uraian }}</div>
                                <small
                                    class="text-muted">{{ $e->toko_vendor ? 'Toko/Vendor: ' . $e->toko_vendor : 'Vendor N/A' }}</small>
                            </td>
                            <td class="fw-bold text-dark text-nowrap">Rp {{ number_format($e->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($e->receipts->count() > 0)
                                    <a href="{{ route('expenses.show', $e->id) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-2">
                                        <i class="bi bi-paperclip me-1"></i> {{ $e->receipts->count() }} Nota
                                    </a>
                                @else
                                    <span class="text-muted small">Tanpa Nota</span>
                                @endif
                            </td>
                            <td>
                                @php $badge = $e->status_badge; @endphp
                                <span class="badge {{ $badge['class'] }} rounded-pill px-2.5 py-1.5"
                                    style="font-size: 0.78rem;">
                                    <i class="bi {{ $badge['icon'] }} me-1"></i> {{ $e->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <a href="{{ route('expenses.show', $e->id) }}"
                                        class="btn btn-sm btn-outline-primary rounded-2" title="Detail & Timeline">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @hasanyrole('Superadmin|School Admin|Bendahara')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            Proses
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li>
                                                <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Diajukan">
                                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="bi bi-send text-warning"></i> Ajukan Klaim
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Disetujui">
                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center gap-2 text-info">
                                                        <i class="bi bi-check-lg"></i> Setujui Klaim
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Dibayar">
                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center gap-2 text-success fw-bold">
                                                        <i class="bi bi-cash-coin"></i> Cairkan (Dibayar)
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a href="{{ route('expenses.edit', $e->id) }}"
                                                    class="dropdown-item d-flex align-items-center gap-2 text-secondary">
                                                    <i class="bi bi-pencil"></i> Edit Transaksi
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                        <i class="bi bi-x-circle"></i> Tolak Klaim
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('expenses.destroy', $e->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus catatan pengeluaran talangan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                        <i class="bi bi-trash"></i> Hapus Permanen
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 text-secondary d-block mb-2"></i>
                                Belum ada catatan talangan pengeluaran. Klik <strong>"Input Talangan"</strong>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- Modal Fast Entry Expense (<30s) -->
    <div class="modal fade" id="newExpenseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-lightning-fill text-warning me-2"></i>Input Talangan
                        Pribadi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Pengeluaran BOSP <span
                                    class="text-danger">*</span></label>
                            <select name="expense_category_id" class="form-select bg-light" required>
                                <option value="">-- Pilih Kategori BOSP --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }} ({{ $cat->kode_bosp ?: 'BOSP' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tanggal Transaksi <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control bg-light" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Nominal Talangan (Rp) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                    <input type="text" inputmode="numeric" name="nominal" id="nominal" class="form-control bg-light fw-bold rupiah-input"
                                        placeholder="50.000" required autocomplete="off">
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                    <i class="bi bi-info-circle me-1"></i>Hanya angka tanpa simbol dan huruf
                                </small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Uraian / Keperluan <span
                                    class="text-danger">*</span></label>
                            <textarea name="uraian" rows="2" class="form-control bg-light"
                                placeholder="Contoh: Beli Kertas HVS 2 Rim & Map Raport" required></textarea>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Toko / Vendor</label>
                                <input type="text" name="toko_vendor" class="form-control bg-light"
                                    placeholder="Contoh: Toko ATK Sejahtera">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control bg-light"
                                    placeholder="Contoh: Sukajadi">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Foto Struk / Nota (Multi-Upload)</label>
                            <input type="file" name="receipts[]" class="form-control bg-light"
                                accept="image/*,application/pdf" multiple>
                            <small class="text-muted">Mendukung file JPG, PNG, HEIC, atau PDF (Maks 5MB per file)</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                            <i class="bi bi-check-lg me-1"></i> Simpan Catatan Talangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Input/Edit Dana BOSP Awal Cair -->
    <div class="modal fade" id="bospModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bi bi-cash-coin text-primary me-2"></i>{{ $danaBosp ? 'Perbarui Dana BOSP Awal Cair' : 'Input Dana BOSP Awal Cair' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('expenses.dana-bosp.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tahun Anggaran <span class="text-danger">*</span></label>
                                <select name="tahun" class="form-select bg-light" required>
                                    @for($y = date('Y') + 1; $y >= 2024; $y--)
                                        <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tahap / Periode <span class="text-danger">*</span></label>
                                <select name="periode" class="form-select bg-light" required>
                                    <option value="Tahap 1 (Semester I)" {{ $currentPeriode == 'Tahap 1 (Semester I)' ? 'selected' : '' }}>Tahap 1 (Semester I)</option>
                                    <option value="Tahap 2 (Semester II)" {{ $currentPeriode == 'Tahap 2 (Semester II)' ? 'selected' : '' }}>Tahap 2 (Semester II)</option>
                                    <option value="Triwulan 1" {{ $currentPeriode == 'Triwulan 1' ? 'selected' : '' }}>Triwulan 1</option>
                                    <option value="Triwulan 2" {{ $currentPeriode == 'Triwulan 2' ? 'selected' : '' }}>Triwulan 2</option>
                                    <option value="Triwulan 3" {{ $currentPeriode == 'Triwulan 3' ? 'selected' : '' }}>Triwulan 3</option>
                                    <option value="Triwulan 4" {{ $currentPeriode == 'Triwulan 4' ? 'selected' : '' }}>Triwulan 4</option>
                                    <option value="Tahunan" {{ $currentPeriode == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Sumber Dana BOSP <span class="text-danger">*</span></label>
                                <select name="sumber_dana" class="form-select bg-light" required>
                                    <option value="BOSP Reguler" {{ ($danaBosp?->sumber_dana == 'BOSP Reguler') ? 'selected' : '' }}>BOSP Reguler (Kemendikbud)</option>
                                    <option value="BOSP Kinerja" {{ ($danaBosp?->sumber_dana == 'BOSP Kinerja') ? 'selected' : '' }}>BOSP Kinerja</option>
                                    <option value="BOSP Daerah / BOSDA" {{ ($danaBosp?->sumber_dana == 'BOSP Daerah / BOSDA') ? 'selected' : '' }}>BOSP Daerah / BOSDA</option>
                                    <option value="BOS Afirmasi" {{ ($danaBosp?->sumber_dana == 'BOS Afirmasi') ? 'selected' : '' }}>BOS Afirmasi</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nominal Dana Cair Awal (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                    <input type="text" inputmode="numeric" name="nominal_cair" class="form-control bg-light fw-bold rupiah-input"
                                        placeholder="50.000.000" value="{{ $danaBosp ? number_format($danaBosp->nominal_cair, 0, ',', '.') : '' }}" required autocomplete="off">
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                    <i class="bi bi-info-circle me-1"></i>Hanya angka tanpa simbol dan huruf (otomatis terformat)
                                </small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tanggal Pencairan / Masuk Rekening</label>
                                <input type="date" name="tanggal_cair" class="form-control bg-light" value="{{ $danaBosp && $danaBosp->tanggal_cair ? $danaBosp->tanggal_cair->format('Y-m-d') : date('Y-m-d') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan / Keterangan Pencairan</label>
                                <textarea name="catatan" rows="2" class="form-control bg-light" placeholder="Contoh: Pencairan BOSP Reguler Tahap 1 via Bank Jabar/BJB">{{ $danaBosp?->catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                            <i class="bi bi-check-lg me-1"></i> Simpan Dana BOSP Cair
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatRupiah(value) {
                let numberString = value.replace(/[^,\d]/g, '').toString();
                let split = numberString.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            document.querySelectorAll('.rupiah-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    this.value = formatRupiah(this.value);
                });
                if (input.value) {
                    input.value = formatRupiah(input.value);
                }
            });
        });
    </script>
@endsection