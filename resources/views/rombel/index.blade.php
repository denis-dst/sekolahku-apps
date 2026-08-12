@extends('layouts.app')

@section('title', 'Rombel & Kelas - SekolahKu')
@section('page_title', 'Manajemen Rombel (Rombongan Belajar)')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="bi bi-building me-2 text-primary"></i>Daftar Rombongan Belajar</h5>
        <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newRombelModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Rombel Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
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
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-bold text-dark">{{ $r->nama_rombel }}</td>
                        <td><span class="badge bg-primary">{{ $r->tingkat }}</span></td>
                        <td>{{ $r->tahunAjaran->name ?? '-' }}</td>
                        <td>{{ $r->waliKelas->nama_lengkap ?? 'Belum Ditentukan' }}</td>
                        <td><span class="badge bg-secondary">{{ $r->siswas->count() }} Siswa</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada Rombel dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal New Rombel -->
<div class="modal fade" id="newRombelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Rombel / Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('rombel.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="form-select" required>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->name }} (Semester {{ $ta->semester }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Rombel</label>
                        <input type="text" name="nama_rombel" class="form-control" placeholder="Contoh: TK-A1, Kelas 1-A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tingkat</label>
                        <input type="text" name="tingkat" class="form-control" placeholder="Contoh: A, B, 1, 7, 10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Wali Kelas</label>
                        <select name="guru_id" class="form-select">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Rombel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
