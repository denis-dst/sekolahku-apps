@extends('layouts.app')

@section('title', 'Dashboard - SekolahKu')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Total Siswa Aktif</span>
                <h3 class="fw-bold m-0 text-dark">{{ $stats['total_siswa'] }}</h3>
            </div>
            <div class="kpi-icon bg-primary-subtle text-primary">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Presensi Hari Ini</span>
                <h3 class="fw-bold m-0 text-dark">{{ $stats['presensi_hadir'] }} <span class="fs-6 text-muted">/ {{ $stats['presensi_today'] }}</span></h3>
            </div>
            <div class="kpi-icon bg-success-subtle text-success">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Verifikasi SPP</span>
                <h3 class="fw-bold m-0 text-warning">{{ $stats['pending_spp'] }}</h3>
            </div>
            <div class="kpi-icon bg-warning-subtle text-warning">
                <i class="bi bi-qr-code"></i>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-custom p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Talangan Diajukan</span>
                <h3 class="fw-bold m-0 text-danger">{{ $stats['pending_expense'] }}</h3>
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
            <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat Fitur</h5>
            <div class="row g-3">
                @role('Siswa')
                <div class="col-6 col-md-4">
                    <a href="{{ route('presensi.mandiri') }}" class="btn btn-outline-primary w-100 p-3 text-start rounded-3">
                        <i class="bi bi-qr-code-scan fs-3 d-block mb-1"></i>
                        <span class="fw-bold d-block">Absen Mandiri</span>
                        <small class="text-muted">Absen pagi siswa</small>
                    </a>
                </div>
                @endrole

                @hasanyrole('Superadmin|School Admin|Guru')
                <div class="col-6 col-md-4">
                    <a href="{{ route('presensi.index') }}" class="btn btn-outline-primary w-100 p-3 text-start rounded-3">
                        <i class="bi bi-calendar2-check fs-3 d-block mb-1"></i>
                        <span class="fw-bold d-block">Presensi Kelas</span>
                        <span class="small text-muted">Input presensi pagi</span>
                    </a>
                </div>
                @endhasanyrole

                @hasanyrole('Superadmin|School Admin|Bendahara')
                <div class="col-6 col-md-4">
                    <a href="{{ route('spp.verifikasi.queue') }}" class="btn btn-outline-warning w-100 p-3 text-start rounded-3">
                        <i class="bi bi-check2-circle fs-3 d-block mb-1"></i>
                        <span class="fw-bold d-block">Verifikasi QRIS</span>
                        <span class="small text-muted">Cek bukti bayar SPP</span>
                    </a>
                </div>
                @endhasanyrole

                @hasanyrole('Superadmin|School Admin|Bendahara|Guru')
                <div class="col-6 col-md-4">
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-success w-100 p-3 text-start rounded-3">
                        <i class="bi bi-plus-circle-fill fs-3 d-block mb-1"></i>
                        <span class="fw-bold d-block">Input Talangan</span>
                        <span class="small text-muted">&lt;30 detik entry</span>
                    </a>
                </div>
                @endhasanyrole

                @hasanyrole('Superadmin|School Admin|Guru|Orang Tua|Siswa')
                <div class="col-6 col-md-4">
                    <a href="{{ route('erapor.index') }}" class="btn btn-outline-danger w-100 p-3 text-start rounded-3">
                        <i class="bi bi-file-earmark-pdf fs-3 d-block mb-1"></i>
                        <span class="fw-bold d-block">E-Rapor Digital</span>
                        <span class="small text-muted">Cetak rapor & narasi</span>
                    </a>
                </div>
                @endhasanyrole
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="bi bi-receipt me-2 text-primary"></i>Pengeluaran Talangan Terbaru (BendaharaKu)</h5>
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-link">Lihat Semua</a>
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
                                    <span class="badge bg-{{ $exp->status == 'Dibayar' ? 'success' : ($exp->status == 'Diajukan' ? 'warning' : 'secondary') }}">
                                        {{ $exp->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada catatan pengeluaran talangan.</td>
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
                <h5 class="fw-bold m-0"><i class="bi bi-clock-history me-2 text-warning"></i>Verifikasi SPP Pending</h5>
                <a href="{{ route('spp.verifikasi.queue') }}" class="btn btn-sm btn-outline-warning">Proses</a>
            </div>

            @forelse($recentSppPending as $spp)
                <div class="p-3 bg-light rounded-3 mb-2 border">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-bold text-dark">{{ $spp->siswa->nama_lengkap }}</span>
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
