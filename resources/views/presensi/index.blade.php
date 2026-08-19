@extends('layouts.app')

@section('title', 'Presensi Harian Siswa - SekolahKu')
@section('page_title', 'Presensi Harian Kelas')

@section('content')
<!-- Header Action Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-calendar-check-fill me-2 text-primary"></i>Presensi Harian Siswa</h5>
        <small class="text-muted">Pencatatan kehadiran harian siswa per rombongan belajar oleh Guru Kelas / Wali Kelas</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('presensi.rekap', ['rombel_id' => $selectedRombelId]) }}" class="btn btn-outline-danger btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Lihat Rekap Bulanan & Cetak PDF
        </a>
    </div>
</div>

<!-- Filter Selection Card -->
<div class="card-custom p-4 mb-4">
    <form action="{{ route('presensi.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
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
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold text-secondary small">Tanggal Presensi <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" class="form-control bg-light" value="{{ $tanggal }}" onchange="this.form.submit()">
        </div>
        <div class="col-12 col-md-3">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                <i class="bi bi-search me-1"></i> Tampilkan Siswa
            </button>
        </div>
    </form>
</div>

@if($siswas->count() > 0)
    <form action="{{ route('presensi.guru.store') }}" method="POST" id="presensiForm">
        @csrf
        <input type="hidden" name="rombel_id" value="{{ $selectedRombelId }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <!-- Quick Actions & Status Summary Card -->
        <div class="card-custom p-4 mb-4 border-top border-4 border-success">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold mb-1">
                        {{ $selectedRombel->nama_rombel ?? 'Rombel' }} &bull; {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </span>
                    <h6 class="fw-bold text-dark mb-0">Total Siswa Terdaftar: {{ $siswas->count() }} Siswa</h6>
                </div>

                <!-- Quick Action "Tandai Semua" (Alur Proses Bisnis Siakad) -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="text-muted small fw-semibold text-uppercase me-1" style="font-size:0.75rem;">Tandai Semua:</span>
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" onclick="markAll('Hadir')">
                        <i class="bi bi-check2-all me-1"></i> Semua Hadir
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold" onclick="markAll('Sakit')">
                        <i class="bi bi-bandaid me-1"></i> Semua Sakit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold" onclick="markAll('Izin')">
                        <i class="bi bi-envelope-paper me-1"></i> Semua Izin
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" onclick="markAll('Alpa')">
                        <i class="bi bi-x-circle me-1"></i> Semua Alpa
                    </button>
                </div>
            </div>
        </div>

        <!-- Student Attendance Table Card -->
        <div class="card-custom p-4 mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        <tr>
                            <th style="width: 45px;" class="text-center">No</th>
                            <th style="width: 250px;">Nama Siswa</th>
                            <th style="width: 110px;">NISN / NIS</th>
                            <th class="text-center" style="width: 380px;">Status Kehadiran</th>
                            <th>Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $idx => $s)
                            @php
                                $p = $presensis->get($s->id);
                                $currentStatus = $p?->status ?? 'Hadir';
                            @endphp
                            <tr>
                                <td class="text-center fw-semibold text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $s->nama_lengkap }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted">{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</small>
                                        @if($p?->entry_type == 'siswa_mandiri')
                                            <span class="badge bg-secondary-subtle text-dark border" style="font-size:0.65rem;">
                                                <i class="bi bi-phone me-1"></i> Mandiri ({{ $p->jam_masuk }})
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="font-monospace text-muted small">{{ $s->nisn ?: '-' }}</td>
                                <td class="text-center">
                                    <!-- Interactive Pill Radio Group -->
                                    <div class="btn-group btn-group-sm w-100 shadow-sm" role="group">
                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="h_{{ $s->id }}" value="Hadir" data-student="{{ $s->id }}" {{ $currentStatus == 'Hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success fw-semibold" for="h_{{ $s->id }}">Hadir</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="s_{{ $s->id }}" value="Sakit" data-student="{{ $s->id }}" {{ $currentStatus == 'Sakit' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning fw-semibold" for="s_{{ $s->id }}">Sakit</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="i_{{ $s->id }}" value="Izin" data-student="{{ $s->id }}" {{ $currentStatus == 'Izin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info fw-semibold" for="i_{{ $s->id }}">Izin</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="a_{{ $s->id }}" value="Alpa" data-student="{{ $s->id }}" {{ $currentStatus == 'Alpa' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger fw-semibold" for="a_{{ $s->id }}">Alpa</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="t_{{ $s->id }}" value="Terlambat" data-student="{{ $s->id }}" {{ $currentStatus == 'Terlambat' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary fw-semibold" for="t_{{ $s->id }}">Terlambat</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="catatan[{{ $s->id }}]" class="form-control form-control-sm bg-light" placeholder="Alasan izin / sakit / keterangan..." value="{{ $p?->catatan }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Action Footer -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top mt-3">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1 text-primary"></i> Perubahan status tidak hadir (Sakit/Izin/Alpa) otomatis mengirimkan notifikasi WA ke kontak orang tua.
                </div>
                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle fs-5"></i> Simpan Presensi Kelas
                </button>
            </div>
        </div>
    </form>
@elseif($selectedRombelId)
    <div class="card-custom p-5 text-center text-muted">
        <i class="bi bi-people fs-1 text-secondary d-block mb-2"></i>
        Belum ada siswa aktif yang terdaftar di dalam rombel <strong>{{ $selectedRombel->nama_rombel ?? 'ini' }}</strong>.
    </div>
@else
    <div class="card-custom p-5 text-center text-muted">
        <i class="bi bi-arrow-up-circle fs-1 text-primary d-block mb-2"></i>
        Silakan pilih <strong>Rombel / Kelas</strong> pada opsi di atas untuk menginput presensi harian.
    </div>
@endif

@push('scripts')
<script>
function markAll(status) {
    const radioValues = {
        'Hadir': 'h_',
        'Sakit': 's_',
        'Izin': 'i_',
        'Alpa': 'a_',
        'Terlambat': 't_'
    };

    const prefix = radioValues[status];
    if (!prefix) return;

    document.querySelectorAll('.status-radio[value="' + status + '"]').forEach(function(radio) {
        radio.checked = true;
    });
}
</script>
@endpush
@endsection
