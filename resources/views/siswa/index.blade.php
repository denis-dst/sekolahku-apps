@extends('layouts.app')

@section('title', 'Data Siswa - SekolahKu')
@section('page_title', 'Manajemen Data Siswa')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Siswa Terdaftar</h5>
        <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newSiswaModal">
            <i class="bi bi-person-plus me-1"></i> Tambah Siswa Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NISN / NIK</th>
                    <th>Rombel</th>
                    <th>Orang Tua / No. HP</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $idx => $s)
                    <tr>
                        <td>{{ $siswas->firstItem() + $idx }}</td>
                        <td class="fw-semibold">{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->nisn ?: '-' }} / {{ $s->nik ?: '-' }}</td>
                        <td><span class="badge bg-secondary-subtle text-dark border">{{ $s->rombel->nama_rombel ?? 'Belum Ada' }}</span></td>
                        <td>
                            <div>{{ $s->nama_ortu ?: '-' }}</div>
                            <small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i> {{ $s->no_hp_ortu ?: '-' }}</small>
                        </td>
                        <td><span class="badge bg-success">{{ $s->status }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data siswa terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $siswas->links() }}
    </div>
</div>

<!-- Modal New Siswa -->
<div class="modal fade" id="newSiswaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Data Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap Siswa</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">NISN</label>
                            <input type="text" name="nisn" class="form-control" placeholder="0011223344">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Pilih Rombel / Kelas</label>
                            <select name="rombel_id" class="form-select">
                                <option value="">-- Pilih Rombel --</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_rombel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_ortu" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">No. HP WA Orang Tua</label>
                            <input type="text" name="no_hp_ortu" class="form-control" placeholder="081234567890">
                        </div>
                        <div class="col-12 border-top pt-3">
                            <label class="form-label fw-semibold text-primary">Buat Akun Siswa (Opsional untuk Absen Mandiri)</label>
                            <div class="row g-2">
                                <div class="col-6"><input type="email" name="email_siswa" class="form-control" placeholder="email.siswa@sekolah.sch.id"></div>
                                <div class="col-6"><input type="password" name="password" class="form-control" placeholder="Kata Sandi Login"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
