@extends('layouts.app')

@section('title', 'Presensi Harian Siswa - SekolahKu')
@section('page_title', 'Presensi Harian Kelas')

@section('content')
<!-- Header Action Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2.5 mb-4">
    <div>
        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-calendar-check-fill me-2 text-primary"></i>Presensi Harian Siswa</h5>
        <small class="text-muted">Pencatatan kehadiran harian siswa per rombongan belajar oleh Guru Kelas / Wali Kelas</small>
    </div>
    <div class="d-flex gap-2 w-100 w-md-auto">
        <a href="{{ route('presensi.rekap', ['rombel_id' => $selectedRombelId]) }}" class="btn btn-outline-danger btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" style="min-height: 38px;">
            <i class="bi bi-file-earmark-spreadsheet-fill"></i> Lihat Rekap Bulanan & PDF
        </a>
    </div>
</div>

<!-- Filter Selection Card -->
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <form action="{{ route('presensi.index') }}" method="GET" class="row g-2.5 align-items-end" id="filterForm">
        <div class="col-12 col-md-5">
            <label class="form-label fw-semibold text-secondary small">Pilih Rombel / Kelas <span class="text-danger">*</span></label>
            <select name="rombel_id" class="form-select bg-light" onchange="this.form.submit()" style="min-height: 42px;">
                <option value="">-- Pilih Rombel --</option>
                @foreach($rombels as $r)
                    <option value="{{ $r->id }}" {{ $selectedRombelId == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }} | Wali: {{ $r->waliKelas?->nama ?? 'Belum Ditentukan' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <label class="form-label fw-semibold text-secondary small">Tanggal Presensi <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" class="form-control bg-light" value="{{ $tanggal }}" onchange="this.form.submit()" style="min-height: 42px;">
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <button type="submit" class="btn btn-primary w-100 fw-semibold" style="min-height: 42px;">
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
        <div class="card-custom p-3 p-sm-4 mb-4 border-top border-4 border-success bg-white">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-2 fw-semibold mb-1">
                        {{ $selectedRombel->nama_rombel ?? 'Rombel' }} &bull; {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </span>
                    <h6 class="fw-bold text-dark mb-0">Total Siswa Terdaftar: {{ $siswas->count() }} Siswa</h6>
                </div>

                <!-- Quick Action "Tandai Semua" -->
                <div class="d-flex flex-wrap align-items-center gap-1.5 w-100 w-lg-auto">
                    <span class="text-muted small fw-semibold text-uppercase me-1 w-100 w-sm-auto" style="font-size:0.75rem;">Tandai Semua:</span>
                    <button type="button" class="btn btn-sm btn-outline-success rounded-2 px-2.5 py-1 fw-bold flex-fill flex-sm-grow-0" style="min-height: 36px;" onclick="markAll('Hadir')">
                        <i class="bi bi-check2-all me-1"></i> Hadir
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning rounded-2 px-2.5 py-1 fw-bold flex-fill flex-sm-grow-0" style="min-height: 36px;" onclick="markAll('Sakit')">
                        <i class="bi bi-bandaid me-1"></i> Sakit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info rounded-2 px-2.5 py-1 fw-bold flex-fill flex-sm-grow-0" style="min-height: 36px;" onclick="markAll('Izin')">
                        <i class="bi bi-envelope-paper me-1"></i> Izin
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-2 px-2.5 py-1 fw-bold flex-fill flex-sm-grow-0" style="min-height: 36px;" onclick="markAll('Alpa')">
                        <i class="bi bi-x-circle me-1"></i> Alpa
                    </button>
                </div>
            </div>
        </div>

        <!-- Student Attendance Table Card -->
        <div class="card-custom p-3 p-sm-4 mb-4 bg-white">
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
                                            <span class="badge bg-secondary-subtle text-dark border rounded-2" style="font-size:0.68rem;">
                                                <i class="bi bi-phone me-1"></i> Mandiri ({{ $p->jam_masuk }})
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="font-monospace text-muted small">{{ $s->nisn ?: '-' }}</td>
                                <td class="text-center">
                                    <!-- Interactive Radio Group with comfortable tap target -->
                                    <div class="btn-group btn-group-sm w-100 shadow-2xs" role="group">
                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="h_{{ $s->id }}" value="Hadir" data-student="{{ $s->id }}" {{ $currentStatus == 'Hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success fw-semibold py-1.5" for="h_{{ $s->id }}">Hadir</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="s_{{ $s->id }}" value="Sakit" data-student="{{ $s->id }}" {{ $currentStatus == 'Sakit' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning fw-semibold py-1.5" for="s_{{ $s->id }}">Sakit</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="i_{{ $s->id }}" value="Izin" data-student="{{ $s->id }}" {{ $currentStatus == 'Izin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info fw-semibold py-1.5" for="i_{{ $s->id }}">Izin</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="a_{{ $s->id }}" value="Alpa" data-student="{{ $s->id }}" {{ $currentStatus == 'Alpa' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger fw-semibold py-1.5" for="a_{{ $s->id }}">Alpa</label>

                                        <input type="radio" class="btn-check status-radio" name="presensi[{{ $s->id }}]" id="t_{{ $s->id }}" value="Terlambat" data-student="{{ $s->id }}" {{ $currentStatus == 'Terlambat' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary fw-semibold py-1.5" for="t_{{ $s->id }}">Terlambat</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="catatan[{{ $s->id }}]" class="form-control form-control-sm bg-light" placeholder="Alasan izin / sakit / keterangan..." value="{{ $p?->catatan }}" style="min-height: 36px;">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Action Footer -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-3 pt-3 border-top mt-3">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1 text-primary"></i> Perubahan status tidak hadir otomatis mengirimkan notifikasi WA ke orang tua.
                </div>
                <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-xs d-flex align-items-center justify-content-center gap-2" style="min-height: 44px;">
                    <i class="bi bi-check2-circle fs-5"></i> Simpan Presensi Kelas
                </button>
            </div>
        </div>
    </form>
@elseif($selectedRombelId)
    <div class="card-custom p-5 text-center text-muted bg-white">
        <i class="bi bi-people fs-1 text-secondary d-block mb-2"></i>
        Belum ada siswa aktif yang terdaftar di dalam rombel <strong>{{ $selectedRombel->nama_rombel ?? 'ini' }}</strong>.
    </div>
@else
    <div class="card-custom p-5 text-center text-muted bg-white">
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
