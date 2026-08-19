@extends('layouts.app')

@section('title', 'Dashboard - SekolahKu')
@section('page_title', 'Dashboard Overview')

@section('content')
    <!-- Hero Welcome Banner (siakad-ridho style) -->
    <div class="hero-banner mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <span class="text-xs fw-bold text-uppercase tracking-wider"
                style="color:#0f766e; letter-spacing:1.5px; font-size:0.75rem;">PANEL UTAMA SIM SEKOLAH</span>
            <h3 class="fw-bold mt-1 mb-2" style="color:#0f172a; font-family:'Outfit';">Selamat Datang,
                {{ Auth::user()->name }}! 👋</h3>
            <p class="text-muted m-0 small">Kelola presensi, pembayaran SPP, keuangan sekolah, dan e-rapor dalam satu
                platform digital terpadu.</p>
        </div>
        <div>
            <span class="badge bg-white text-dark shadow-sm px-3 py-2.5 rounded-3 border d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary fs-6"></i>
                <span class="small fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</span>
            </span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size:0.72rem;">Total Siswa
                        Aktif</span>
                    <h3 class="fw-bold m-0 mt-1" style="color:#0f172a;">{{ $stats['total_siswa'] }}</h3>
                </div>
                <div class="kpi-icon" style="background-color:#f0fdfa; color:#0f766e;">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size:0.72rem;">Presensi Hari
                        Ini</span>
                    <h3 class="fw-bold m-0 mt-1" style="color:#0f172a;">{{ $stats['presensi_hadir'] }} <span
                            class="fs-6 text-muted">/ {{ $stats['presensi_today'] }}</span></h3>
                </div>
                <div class="kpi-icon" style="background-color:#ecfdf5; color:#047857;">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size:0.72rem;">Verifikasi
                        SPP</span>
                    <h3 class="fw-bold m-0 mt-1" style="color:#b45309;">{{ $stats['pending_spp'] }}</h3>
                </div>
                <div class="kpi-icon" style="background-color:#fffbeb; color:#b45309;">
                    <i class="bi bi-qr-code"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-custom p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase" style="font-size:0.72rem;">Talangan
                        Diajukan</span>
                    <h3 class="fw-bold m-0 mt-1 text-danger">{{ $stats['pending_expense'] }}</h3>
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
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3" style="color:#0f172a;"><i
                        class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat Fitur</h5>
                <div class="row g-3">
                    @role('Siswa')
                    <div class="col-6">
                        <a href="{{ route('presensi.mandiri') }}"
                            class="btn btn-outline-primary w-100 h-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-qr-code-scan fs-3 d-block mb-1"></i>
                                <span class="fw-bold d-block">Absen Mandiri</span>
                            </div>
                            <span class="small text-muted mt-1">Absen pagi siswa</span>
                        </a>
                    </div>
                    @endrole

                    @hasanyrole('Superadmin|School Admin|Guru')
                    <div class="col-6">
                        <a href="{{ route('presensi.index') }}"
                            class="btn btn-outline-primary w-100 h-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-calendar2-check fs-3 d-block mb-1"></i>
                                <span class="fw-bold d-block">Presensi Kelas</span>
                            </div>
                            <span class="small text-muted mt-1">Input presensi pagi</span>
                        </a>
                    </div>
                    @endhasanyrole

                    @hasanyrole('Superadmin|School Admin|Bendahara')
                    <div class="col-6">
                        <a href="{{ route('spp.verifikasi.queue') }}"
                            class="btn btn-outline-warning w-100 h-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-check2-circle fs-3 d-block mb-1"></i>
                                <span class="fw-bold d-block">Verifikasi QRIS</span>
                            </div>
                            <span class="small text-muted mt-1">Cek bukti bayar SPP</span>
                        </a>
                    </div>
                    @endhasanyrole

                    @hasanyrole('Superadmin|School Admin|Bendahara|Guru')
                    <div class="col-6">
                        <a href="{{ route('expenses.index') }}"
                            class="btn btn-outline-success w-100 h-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-plus-circle-fill fs-3 d-block mb-1"></i>
                                <span class="fw-bold d-block">Input Talangan</span>
                            </div>
                            <span class="small text-muted mt-1">Catat nota & reimburse</span>
                        </a>
                    </div>
                    @endhasanyrole

                    @hasanyrole('Superadmin|School Admin|Guru|Orang Tua|Siswa')
                    <div class="col-6">
                        <a href="{{ route('erapor.index') }}" class="btn btn-outline-danger w-100 h-100 p-3 text-start rounded-3 d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-file-earmark-pdf fs-3 d-block mb-1"></i>
                                <span class="fw-bold d-block">E-Rapor Digital</span>
                            </div>
                            <span class="small text-muted mt-1">Cetak rapor & narasi</span>
                        </a>
                    </div>
                    @endhasanyrole
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0" style="color:#0f172a;"><i
                            class="bi bi-receipt me-2 text-primary"></i>Pengeluaran Talangan Terbaru (BendaharaKu)</h5>
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-link text-decoration-none"
                        style="color:#0f766e; font-weight:600;">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pengaju</th>
                                <th>Uraian</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses as $exp)
                                <tr>
                                    <td>{{ $exp->tanggal->format('d/m/Y') }}</td>
                                    <td><span class="fw-semibold">{{ $exp->user->name }}</span></td>
                                    <td>{{ $exp->uraian }}</td>
                                    <td class="fw-bold">Rp {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $exp->status == 'Dibayar' ? 'success' : ($exp->status == 'Diajukan' ? 'warning' : 'secondary') }}">
                                            {{ $exp->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada catatan pengeluaran talangan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: Pending SPP Verifications -->
        <div class="col-12 col-lg-4">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0" style="color:#0f172a;"><i
                            class="bi bi-clock-history me-2 text-warning"></i>Verifikasi SPP Pending</h5>
                    <a href="{{ route('spp.verifikasi.queue') }}" class="btn btn-sm btn-outline-warning">Proses</a>
                </div>

                @forelse($recentSppPending as $spp)
                    <div class="p-3 rounded-3 mb-2 border" style="background-color:#f8fafc; border-color:#e2e8f0!important;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="fw-bold" style="color:#0f172a;">{{ $spp->siswa->nama_lengkap }}</span>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </div>
                        <div class="small text-muted">Bulan: {{ $spp->bulan }} {{ $spp->tahun }}</div>
                        <div class="fw-bold text-primary mt-1">Rp {{ number_format($spp->total_tagihan, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Tidak ada antrean verifikasi SPP saat ini.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection