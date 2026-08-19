@extends('layouts.app')

@section('title', 'E-Rapor Digital - SekolahKu')
@section('page_title', 'E-Rapor & Penilaian')

@section('content')
<div class="card-custom p-4 mb-4">
    <form action="{{ route('erapor.index') }}" method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-12 col-md-6">
            <label class="form-label fw-semibold">Pilih Rombel / Kelas</label>
            <select name="rombel_id" class="form-select" onchange="this.form.submit()">
                @foreach($rombels as $r)
                    <option value="{{ $r->id }}" {{ $selectedRombelId == $r->id ? 'selected' : '' }}>
                        {{ $r->nama_rombel }} (Tingkat {{ $r->tingkat }})
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Data Rapor Siswa Rombel</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NISN</th>
                    <th>Jumlah Assessment</th>
                    <th class="text-center">Input Nilai</th>
                    <th class="text-center">Cetak PDF</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $idx => $s)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-semibold">{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->nisn ?: '-' }}</td>
                        <td><span class="badge bg-info-subtle text-info border">{{ $s->assessments->count() }} Penilaian</span></td>
                        <td class="text-center">
                            @hasanyrole('Superadmin|School Admin|Guru')
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#scoreModal{{ $s->id }}">
                                    <i class="bi bi-pencil-square me-1"></i> Input Nilai & Narasi
                                </button>
                            @endhasanyrole
                        </td>
                        <td class="text-center">
                            <a href="{{ route('erapor.pdf', $s->id) }}" class="btn btn-sm btn-danger rounded-3 fw-bold">
                                <i class="bi bi-file-pdf me-1"></i> Download E-Rapor PDF
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Score Input -->
                    <div class="modal fade" id="scoreModal{{ $s->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Input Penilaian Rapor - {{ $s->nama_lengkap }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('erapor.assessment.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Elemen / Mata Pelajaran</label>
                                            <select name="mata_pelajaran" class="form-select">
                                                <option value="Nilai Agama dan Budi Pekerti">Nilai Agama dan Budi Pekerti</option>
                                                <option value="Jati Diri">Jati Diri</option>
                                                <option value="Dasar-dasar Literasi & STEAM">Dasar-dasar Literasi & STEAM</option>
                                                <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                                                <option value="Matematika">Matematika</option>
                                                <option value="Pendidikan Pancasila">Pendidikan Pancasila</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Jenis Penilaian</label>
                                            <select name="jenis_penilaian" class="form-select">
                                                <option value="Sumatif">Sumatif (Capaian Akhir)</option>
                                                <option value="Formatif">Formatif (Proses Harian)</option>
                                                <option value="P5">Projek P5 (Profil Pelajar Pancasila)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nilai Angka (Opsional)</label>
                                            <input type="number" step="0.1" name="nilai_angka" class="form-control" placeholder="85.5">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Deskripsi Capaian Narasi</label>
                                            <textarea name="capaian_narasi" class="form-control" rows="4" placeholder="Ananda menunjukkan perkembangan sangat baik dalam..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Penilaian</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Pilih rombel untuk menampilkan siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
