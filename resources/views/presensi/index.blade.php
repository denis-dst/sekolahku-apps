@extends('layouts.app')

@section('title', 'Presensi Kelas Morning - SekolahKu')
@section('page_title', 'Presensi Harian Rombel (Guru Kelas)')

@section('content')
<div class="card-custom p-4 mb-4">
    <form action="{{ route('presensi.index') }}" method="GET" class="row g-3 align-items-end mb-3">
        <div class="col-12 col-md-5">
            <label class="form-label fw-semibold">Pilih Rombel / Kelas</label>
            <select name="rombel_id" class="form-select" onchange="this.form.submit()">
                @foreach($rombels as $r)
                    <option value="{{ $r->id }}" {{ $selectedRombelId == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label fw-semibold">Tanggal Presensi</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
        </div>
    </form>

    @if($siswas->count() > 0)
        <form action="{{ route('presensi.guru.store') }}" method="POST">
            @csrf
            <input type="hidden" name="rombel_id" value="{{ $selectedRombelId }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-muted">Daftar Siswa Rombel (Total: {{ $siswas->count() }})</span>
                <span class="badge bg-info-subtle text-info border px-3 py-2"><i class="bi bi-whatsapp me-1"></i> Fonnte WA Otomatis Aktif</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th class="text-center">Status Presensi</th>
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
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-semibold">
                                    {{ $s->nama_lengkap }}
                                    @if($p?->entry_type == 'siswa_mandiri')
                                        <span class="badge bg-secondary ms-1" style="font-size:0.65rem;">Mandiri ({{ $p->jam_masuk }})</span>
                                    @endif
                                </td>
                                <td>{{ $s->nisn ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="presensi[{{ $s->id }}]" id="h_{{ $s->id }}" value="Hadir" {{ $currentStatus == 'Hadir' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="h_{{ $s->id }}">Hadir</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $s->id }}]" id="i_{{ $s->id }}" value="Izin" {{ $currentStatus == 'Izin' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info" for="i_{{ $s->id }}">Izin</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $s->id }}]" id="s_{{ $s->id }}" value="Sakit" {{ $currentStatus == 'Sakit' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="s_{{ $s->id }}">Sakit</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $s->id }}]" id="a_{{ $s->id }}" value="Alpa" {{ $currentStatus == 'Alpa' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="a_{{ $s->id }}">Alpa</label>

                                        <input type="radio" class="btn-check" name="presensi[{{ $s->id }}]" id="t_{{ $s->id }}" value="Terlambat" {{ $currentStatus == 'Terlambat' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary" for="t_{{ $s->id }}">Terlambat</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="catatan[{{ $s->id }}]" class="form-control form-control-sm" placeholder="Catatan opsional..." value="{{ $p?->catatan }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Presensi & Kirim WA Orang Tua
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-info text-center py-4">Pilih Rombel terlebih dahulu untuk melakukan presensi.</div>
    @endif
</div>
@endsection
