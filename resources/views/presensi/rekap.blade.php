@extends('layouts.app')

@section('title', 'Rekapitulasi Presensi Bulanan - SekolahKu')
@section('page_title', 'Rekap Presensi Bulanan')

@section('content')
<!-- Header Action Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-file-earmark-spreadsheet-fill me-2 text-primary"></i>Rekap Presensi Bulanan Siswa</h5>
        <small class="text-muted">Laporan matriks kehadiran harian siswa per tanggal dalam satu bulan penuh</small>
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($selectedRombelId && count($recap) > 0)
            <a href="{{ route('presensi.rekap.csv', ['rombel_id' => $selectedRombelId, 'tahun' => $tahun, 'bulan' => $bulan]) }}" class="btn btn-outline-success btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1">
                <i class="bi bi-file-earmark-excel"></i> Export CSV
            </a>
            <a href="{{ route('presensi.rekap.pdf', ['rombel_id' => $selectedRombelId, 'tahun' => $tahun, 'bulan' => $bulan]) }}" target="_blank" class="btn btn-danger btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-file-earmark-pdf-fill"></i> Export PDF Rekap
            </a>
        @endif
        <a href="{{ route('presensi.index', ['rombel_id' => $selectedRombelId]) }}" class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-1">
            <i class="bi bi-pencil-square"></i> Input Presensi Harian
        </a>
    </div>
</div>

<!-- Filter Selection Card -->
<div class="card-custom p-4 mb-4">
    <form action="{{ route('presensi.rekap') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-12 col-md-5">
            <label class="form-label fw-semibold text-secondary small">Pilih Rombel / Kelas <span class="text-danger">*</span></label>
            <select name="rombel_id" class="form-select bg-light" onchange="this.form.submit()">
                <option value="">-- Pilih Rombel --</option>
                @foreach($rombels as $r)
                    <option value="{{ $r->id }}" {{ $selectedRombelId == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }} | Wali: {{ $r->waliKelas?->nama ?? 'Belum Ditentukan' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-3">
            <label class="form-label fw-semibold text-secondary small">Bulan</label>
            <select name="bulan" class="form-select bg-light" onchange="this.form.submit()">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label fw-semibold text-secondary small">Tahun</label>
            <select name="tahun" class="form-select bg-light" onchange="this.form.submit()">
                @for($y = date('Y') + 1; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                <i class="bi bi-filter me-1"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

@if($selectedRombelId && count($recap) > 0)
    <!-- Header Summary Card -->
    <div class="card-custom p-4 mb-4 border-top border-4 border-success">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold mb-1">
                    PERIODE: {{ strtoupper(Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y')) }}
                </span>
                <h5 class="fw-bold text-dark mb-0">Rombel: {{ $selectedRombel->nama_rombel }} (Tingkat {{ $selectedRombel->tingkat }})</h5>
                <small class="text-muted">Wali Kelas: <strong>{{ $selectedRombel->waliKelas?->nama ?? 'Belum Ditentukan' }}</strong> &bull; Total Siswa: {{ count($recap) }} Siswa</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('presensi.rekap.pdf', ['rombel_id' => $selectedRombelId, 'tahun' => $tahun, 'bulan' => $bulan]) }}" target="_blank" class="btn btn-danger px-4 py-2 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf-fill fs-5"></i> Download PDF Rekap
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Matrix Table Card -->
    <div class="card-custom p-4 mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center" style="font-size: 0.82rem;">
                <thead class="table-light align-middle text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <tr>
                        <th rowspan="2" style="width: 40px;" class="text-center">No</th>
                        <th rowspan="2" style="min-width: 180px; text-align: left;" class="text-start">Nama Siswa</th>
                        <th rowspan="2" style="width: 95px;">NISN</th>
                        <th colspan="{{ $daysInMonth }}" class="text-center bg-light">Tanggal (1 s/d {{ $daysInMonth }})</th>
                        <th colspan="5" class="text-center bg-secondary-subtle">Total Kehadiran</th>
                    </tr>
                    <tr>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            <th style="width: 26px; padding: 4px 1px;" class="fw-bold">{{ $d }}</th>
                        @endfor
                        <th style="width: 32px;" class="bg-success-subtle text-success fw-bold">H</th>
                        <th style="width: 32px;" class="bg-warning-subtle text-warning-emphasis fw-bold">S</th>
                        <th style="width: 32px;" class="bg-info-subtle text-info-emphasis fw-bold">I</th>
                        <th style="width: 32px;" class="bg-danger-subtle text-danger fw-bold">A</th>
                        <th style="width: 32px;" class="bg-secondary-subtle text-secondary fw-bold">T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recap as $index => $row)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td class="text-start fw-bold text-dark text-truncate" style="max-width: 200px;">
                                {{ $row['nama_lengkap'] }}
                                <small class="text-muted d-block font-monospace fw-normal" style="font-size: 0.7rem;">JK: {{ $row['jenis_kelamin'] }}</small>
                            </td>
                            <td class="font-monospace text-muted small">{{ $row['nisn'] }}</td>
                            
                            <!-- Daily Matrix Cells -->
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                                    $status = $row['details'][$dateStr] ?? null;
                                @endphp
                                <td style="padding: 4px 1px;">
                                    @if($status === 'Hadir')
                                        <span class="badge bg-success" style="font-size: 0.65rem; padding: 2px 4px;">H</span>
                                    @elseif($status === 'Sakit')
                                        <span class="badge bg-warning text-dark" style="font-size: 0.65rem; padding: 2px 4px;">S</span>
                                    @elseif($status === 'Izin')
                                        <span class="badge bg-info text-dark" style="font-size: 0.65rem; padding: 2px 4px;">I</span>
                                    @elseif($status === 'Alpa')
                                        <span class="badge bg-danger" style="font-size: 0.65rem; padding: 2px 4px;">A</span>
                                    @elseif($status === 'Terlambat')
                                        <span class="badge bg-secondary" style="font-size: 0.65rem; padding: 2px 4px;">T</span>
                                    @else
                                        <span class="text-muted opacity-25">-</span>
                                    @endif
                                </td>
                            @endfor

                            <!-- Summary Counts -->
                            <td class="fw-bold text-success bg-success-subtle">{{ $row['hadir'] }}</td>
                            <td class="fw-bold text-warning-emphasis bg-warning-subtle">{{ $row['sakit'] }}</td>
                            <td class="fw-bold text-info-emphasis bg-info-subtle">{{ $row['izin'] }}</td>
                            <td class="fw-bold text-danger bg-danger-subtle">{{ $row['alpa'] }}</td>
                            <td class="fw-bold text-secondary bg-secondary-subtle">{{ $row['terlambat'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Legend Footer -->
        <div class="d-flex flex-wrap align-items-center gap-3 pt-3 border-top mt-3 small text-muted">
            <span class="fw-bold text-dark">Keterangan Singkatan:</span>
            <span class="d-flex align-items-center gap-1"><span class="badge bg-success">H</span> Hadir</span>
            <span class="d-flex align-items-center gap-1"><span class="badge bg-warning text-dark">S</span> Sakit</span>
            <span class="d-flex align-items-center gap-1"><span class="badge bg-info text-dark">I</span> Izin</span>
            <span class="d-flex align-items-center gap-1"><span class="badge bg-danger">A</span> Alpa / Tanpa Keterangan</span>
            <span class="d-flex align-items-center gap-1"><span class="badge bg-secondary">T</span> Terlambat</span>
        </div>
    </div>
@elseif($selectedRombelId)
    <div class="card-custom p-5 text-center text-muted">
        <i class="bi bi-inbox fs-1 text-secondary d-block mb-2"></i>
        Tidak ada data kehadiran siswa yang tercatat pada periode ini.
    </div>
@else
    <div class="card-custom p-5 text-center text-muted">
        <i class="bi bi-arrow-up-circle fs-1 text-primary d-block mb-2"></i>
        Silakan pilih <strong>Rombel / Kelas</strong> pada opsi di atas untuk menampilkan rekapitulasi presensi bulanan.
    </div>
@endif
@endsection
