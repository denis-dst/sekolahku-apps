@extends('layouts.app')

@section('title', 'E-Rapor Digital - SekolahKu')
@section('page_title', 'E-Rapor & Penilaian')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <form action="{{ route('erapor.index') }}" method="GET" class="row g-2.5 align-items-end mb-4">
        <div class="col-12 col-md-6">
            <label class="form-label fw-semibold text-secondary small">Pilih Rombel / Kelas</label>
            <select name="rombel_id" class="form-select bg-light" onchange="this.form.submit()" style="min-height: 42px;">
                @foreach($rombels as $r)
                    <option value="{{ $r->id }}" {{ $selectedRombelId == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0 text-dark"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Data Rapor Siswa Rombel</h5>
        <span class="badge bg-light text-muted border rounded-2">{{ $siswas->count() }} Siswa</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Jumlah Assessment</th>
                    <th class="text-center" style="width: 170px;">Input Nilai</th>
                    <th class="text-center" style="width: 170px;">Cetak PDF</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $idx => $s)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $idx + 1 }}</td>
                        <td class="fw-semibold text-dark">{{ $s->nama_lengkap }}</td>
                        <td class="font-monospace small text-dark">{{ $s->nisn ?: '-' }}</td>
                        <td><span class="badge bg-info-subtle text-info border border-info-subtle rounded-2">{{ $s->assessments->count() }} Penilaian</span></td>
                        <td class="text-center">
                            @hasanyrole('Superadmin|School Admin|Guru')
                                <button class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#scoreModal{{ $s->id }}" style="min-height: 34px;">
                                    <i class="bi bi-pencil-square me-1"></i> Input Nilai
                                </button>
                            @endhasanyrole
                        </td>
                        <td class="text-center">
                            <a href="{{ route('erapor.pdf', $s->id) }}" target="_blank" class="btn btn-sm btn-danger rounded-2 fw-semibold px-2.5 py-1 text-nowrap shadow-xs" style="min-height: 34px;">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Score Input -->
                    <div class="modal fade" id="scoreModal{{ $s->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark">Input Penilaian Rapor - {{ $s->nama_lengkap }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('erapor.assessment.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                    <div class="modal-body p-3 p-sm-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-secondary small">Elemen / Mata Pelajaran <span class="text-danger">*</span></label>
                                            <select name="mata_pelajaran" class="form-select bg-light" style="min-height: 42px;">
                                                <option value="Nilai Agama dan Budi Pekerti">Nilai Agama dan Budi Pekerti</option>
                                                <option value="Jati Diri">Jati Diri</option>
                                                <option value="Dasar-dasar Literasi & STEAM">Dasar-dasar Literasi & STEAM</option>
                                                <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                                                <option value="Matematika">Matematika</option>
                                                <option value="Pendidikan Pancasila">Pendidikan Pancasila</option>
                                            </select>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-12 col-sm-6">
                                                <label class="form-label fw-semibold text-secondary small">Jenis Penilaian <span class="text-danger">*</span></label>
                                                <select name="jenis_penilaian" class="form-select bg-light" style="min-height: 42px;">
                                                    <option value="Sumatif">Sumatif (Capaian Akhir)</option>
                                                    <option value="Formatif">Formatif (Proses Harian)</option>
                                                    <option value="P5">Projek P5 (Profil Pelajar Pancasila)</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <label class="form-label fw-semibold text-secondary small">Nilai Angka (Opsional)</label>
                                                <input type="number" step="0.1" name="nilai_angka" class="form-control bg-light" placeholder="Contoh: 85.5" style="min-height: 42px;">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-secondary small">Deskripsi Capaian Narasi <span class="text-danger">*</span></label>
                                            <textarea name="capaian_narasi" class="form-control bg-light" rows="4" placeholder="Ananda menunjukkan perkembangan sangat baik dalam..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold" style="min-height: 40px;">Simpan Penilaian</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Pilih rombel untuk menampilkan siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
