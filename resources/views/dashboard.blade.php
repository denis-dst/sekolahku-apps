@extends('layouts.app')

@section('title', 'Dashboard - SekolahKu')
@section('page_title', 'Dashboard Overview')

@section('content')
    <!-- Hero Welcome Banner -->
    <div class="hero-banner mb-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 bg-white">
        <div>
            <span class="fw-bold text-uppercase" style="color: var(--sk-primary); letter-spacing: 1px; font-size: 0.72rem;">PANEL UTAMA SIM SEKOLAH</span>
            <h3 class="fw-bold mt-1 mb-1 text-dark" style="font-family:'Outfit'; font-size: clamp(1.25rem, 2.5vw, 1.65rem);">
                Selamat Datang, {{ Auth::user()->name }}! 👋
            </h3>
            <p class="text-muted m-0 small">Kelola presensi, pembayaran SPP, keuangan sekolah, dan e-rapor dalam satu platform digital terpadu.</p>
        </div>
        <div class="w-100 w-md-auto text-start text-md-end">
            <span class="badge bg-light text-dark px-3 py-2 rounded-2 border d-inline-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary fs-6"></i>
                <span class="small fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</span>
            </span>
        </div>
    </div>

    <!-- Top KPI Summary Cards -->
    <div class="row g-3 mb-4 align-items-stretch">
        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card-custom p-3 w-100 d-flex align-items-center justify-content-between bg-white">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Total Siswa Aktif</span>
                    <h3 class="fw-bold m-0 mt-1 text-dark" style="font-size: clamp(1.3rem, 2vw, 1.6rem);">{{ $stats['total_siswa'] }}</h3>
                </div>
                <div class="kpi-icon" style="background-color: #f0fdfa; color: #0f766e;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card-custom p-3 w-100 d-flex align-items-center justify-content-between bg-white">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Presensi Hari Ini</span>
                    <h3 class="fw-bold m-0 mt-1 text-dark" style="font-size: clamp(1.3rem, 2vw, 1.6rem);">
                        {{ $stats['presensi_hadir'] }} <span class="fs-6 text-muted fw-normal">/ {{ $stats['presensi_today'] }}</span>
                    </h3>
                </div>
                <div class="kpi-icon" style="background-color: #ecfdf5; color: #047857;">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card-custom p-3 w-100 d-flex align-items-center justify-content-between bg-white">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Verifikasi SPP</span>
                    <h3 class="fw-bold m-0 mt-1" style="color: #b45309; font-size: clamp(1.3rem, 2vw, 1.6rem);">{{ $stats['pending_spp'] }}</h3>
                </div>
                <div class="kpi-icon" style="background-color: #fffbeb; color: #b45309;">
                    <i class="bi bi-qr-code"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 d-flex">
            <div class="card-custom p-3 w-100 d-flex align-items-center justify-content-between bg-white">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem;">Talangan Diajukan</span>
                    <h3 class="fw-bold m-0 mt-1 text-danger" style="font-size: clamp(1.3rem, 2vw, 1.6rem);">{{ $stats['pending_expense'] }}</h3>
                </div>
                <div class="kpi-icon bg-danger-subtle text-danger">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Action Quick Links -->
        <div class="col-12 col-lg-8">
            <div class="card-custom p-3 p-sm-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat Fitur</h5>
                <div class="row g-3 align-items-stretch">
                    @role('Siswa')
                    <div class="col-12 col-sm-6 d-flex">
                        <a href="{{ route('presensi.mandiri') }}"
                            class="btn btn-outline-primary w-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between" style="min-height: 90px;">
                            <div>
                                <i class="bi bi-qr-code-scan fs-4 d-block mb-1"></i>
                                <span class="fw-bold d-block">Absen Mandiri</span>
                            </div>
                            <span class="small text-muted mt-1">Absen pagi siswa</span>
                        </a>
                    </div>
                    @endrole

                    @hasanyrole('Superadmin|School Admin|Guru')
                    <div class="col-12 col-sm-6 col-md-4 d-flex">
                        <a href="{{ route('presensi.index') }}"
                            class="btn btn-outline-primary w-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between" style="min-height: 90px;">
                            <div>
                                <i class="bi bi-calendar2-check fs-4 d-block mb-1"></i>
                                <span class="fw-bold d-block">Presensi Kelas</span>
                            </div>
                            <span class="small text-muted mt-1">Input presensi pagi</span>
                        </a>
                    </div>
                    @endhasanyrole

                    @hasanyrole('Superadmin|School Admin|Bendahara')
                    <div class="col-12 col-sm-6 col-md-4 d-flex">
                        <a href="{{ route('spp.verifikasi.queue') }}"
                            class="btn btn-outline-warning w-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between" style="min-height: 90px;">
                            <div>
                                <i class="bi bi-check2-circle fs-4 d-block mb-1"></i>
                                <span class="fw-bold d-block">Verifikasi SPP</span>
                            </div>
                            <span class="small text-muted mt-1">Cek bukti bayar SPP</span>
                        </a>
                    </div>
                    @endhasanyrole

                    @hasanyrole('Superadmin|School Admin|Bendahara|Guru')
                    <div class="col-12 col-sm-6 col-md-4 d-flex">
                        <a href="{{ route('expenses.index') }}"
                            class="btn btn-outline-success w-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between" style="min-height: 90px;">
                            <div>
                                <i class="bi bi-cash-stack fs-4 d-block mb-1"></i>
                                <span class="fw-bold d-block">Input Talangan</span>
                            </div>
                            <span class="small text-muted mt-1">Catat nota & BOSP</span>
                        </a>
                    </div>
                    @endhasanyrole

                    @hasanyrole('Superadmin|School Admin|Guru|Orang Tua|Siswa')
                    <div class="col-12 col-sm-6 col-md-4 d-flex">
                        <a href="{{ route('erapor.index') }}" class="btn btn-outline-danger w-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between" style="min-height: 90px;">
                            <div>
                                <i class="bi bi-file-earmark-pdf fs-4 d-block mb-1"></i>
                                <span class="fw-bold d-block">E-Rapor Digital</span>
                            </div>
                            <span class="small text-muted mt-1">Cetak rapor & narasi</span>
                        </a>
                    </div>
                    @endhasanyrole
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="card-custom p-3 p-sm-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-receipt me-2 text-primary"></i>Pengeluaran Talangan Terbaru (BendaharaKu)
                    </h5>
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: var(--sk-primary);">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Tanggal</th>
                                <th>Pengaju</th>
                                <th>Uraian</th>
                                <th style="width: 130px;">Nominal</th>
                                <th style="width: 100px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses as $exp)
                                <tr>
                                    <td class="text-nowrap fw-medium">{{ $exp->tanggal->format('d/m/Y') }}</td>
                                    <td><span class="fw-semibold text-dark">{{ $exp->user->name ?? '-' }}</span></td>
                                    <td>{{ $exp->uraian }}</td>
                                    <td class="fw-bold text-dark text-nowrap">Rp {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($exp->status) {
                                                'Dibayar' => 'bg-success-subtle text-success border border-success-subtle',
                                                'Diajukan' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                                'Disetujui' => 'bg-info-subtle text-info border border-info-subtle',
                                                default => 'bg-secondary-subtle text-dark border'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-2 px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $exp->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada catatan pengeluaran talangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: Pending SPP Verifications -->
        <div class="col-12 col-lg-4">
            <div class="card-custom p-3 p-sm-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0 text-dark">
                        <i class="bi bi-clock-history me-2 text-warning"></i>Verifikasi SPP Pending
                    </h5>
                    <a href="{{ route('spp.verifikasi.queue') }}" class="btn btn-sm btn-outline-warning rounded-2 px-2.5" style="min-height: 32px;">Proses</a>
                </div>

                @forelse($recentSppPending as $spp)
                    <div class="p-3 rounded-3 mb-2 border bg-light-subtle">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="fw-bold text-dark">{{ $spp->siswa->nama_lengkap ?? '-' }}</span>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-2 px-2 py-0.5" style="font-size: 0.72rem;">Pending</span>
                        </div>
                        <div class="small text-muted">Bulan: {{ $spp->bulan }} {{ $spp->tahun }}</div>
                        <div class="fw-bold text-primary mt-1">Rp {{ number_format($spp->total_tagihan, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4 small">Tidak ada antrean verifikasi SPP saat ini.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection