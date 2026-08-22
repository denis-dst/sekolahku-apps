@extends('layouts.app')

@section('title', 'Rombel & Kelas - SekolahKu')
@section('page_title', 'Rombongan Belajar (Rombel)')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
        <div>
            <h5 class="fw-bold m-0 text-dark"><i class="bi bi-building me-2 text-primary"></i>Daftar Rombongan Belajar</h5>
            <small class="text-muted">Kelola struktur kelas, tahun ajaran, dan penugasan wali kelas</small>
        </div>
        <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newRombelModal" style="min-height: 40px;">
            <i class="bi bi-plus-lg me-1"></i> Tambah Rombel Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Rombel</th>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rombels as $idx => $r)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $idx + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $r->nama_rombel }}</td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2">{{ $r->tingkat }}</span></td>
                        <td>{{ $r->tahunAjaran->name ?? '-' }}</td>
                        <td class="fw-semibold text-dark">{{ $r->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}</td>
                        <td><span class="badge bg-light text-dark border rounded-2">{{ $r->siswas_count ?? $r->siswas->count() }} Siswa</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Belum ada Rombel dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal New Rombel -->
<div class="modal fade" id="newRombelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Rombel / Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('rombel.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select name="tahun_ajaran_id" class="form-select bg-light" required style="min-height: 42px;">
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->name }} (Semester {{ $ta->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-8">
                            <label class="form-label fw-semibold text-secondary small">Nama Rombel <span class="text-danger">*</span></label>
                            <input type="text" name="nama_rombel" class="form-control bg-light" placeholder="Contoh: TK-A1, Kelas 1-A" required style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-sm-4">
                            <label class="form-label fw-semibold text-secondary small">Tingkat <span class="text-danger">*</span></label>
                            <input type="text" name="tingkat" class="form-control bg-light" placeholder="Contoh: A, B, 1" required style="min-height: 42px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Wali Kelas</label>
                        <select name="guru_id" class="form-select bg-light" style="min-height: 42px;">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold" style="min-height: 40px;">Simpan Rombel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
