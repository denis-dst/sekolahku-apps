@extends('layouts.app')

@section('title', 'Data Guru - SekolahKu')
@section('page_title', 'Data Guru & Pendidik')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
        <div>
            <h5 class="fw-bold m-0 text-dark"><i class="bi bi-person-badge-fill me-2 text-primary"></i>Daftar Guru & Staff Pendidik</h5>
            <small class="text-muted">Kelola akun pendidik, NIP/NUPTK, dan penugasan wali kelas</small>
        </div>
        <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#newGuruModal" style="min-height: 40px;">
            <i class="bi bi-person-plus me-1"></i> Tambah Guru Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Lengkap & Gelar</th>
                    <th>NIP / NUPTK</th>
                    <th>Email Account</th>
                    <th>No. HP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $idx => $g)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $gurus->firstItem() + $idx }}</td>
                        <td class="fw-semibold text-dark">{{ $g->nama_lengkap }}</td>
                        <td><span class="badge bg-light text-dark border rounded-2 font-monospace">{{ $g->nip ?: '-' }} / {{ $g->nuptk ?: '-' }}</span></td>
                        <td><span class="small text-muted">{{ $g->user->email ?? '-' }}</span></td>
                        <td><small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i> {{ $g->no_hp ?: '-' }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">Belum ada data guru terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $gurus->links() }}
    </div>
</div>

<!-- Modal New Guru -->
<div class="modal fade" id="newGuruModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Tambah Guru & Akun Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control bg-light" placeholder="Nurhayati, S.Pd." required style="min-height: 42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Alamat Email (Login) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control bg-light" placeholder="guru@sekolah.sch.id" required style="min-height: 42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Kata Sandi</label>
                        <input type="password" name="password" class="form-control bg-light" placeholder="Default: password" style="min-height: 42px;">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold text-secondary small">NIP</label>
                            <input type="text" name="nip" class="form-control bg-light" placeholder="1980xxxx" style="min-height: 42px;">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-semibold text-secondary small">No. HP WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control bg-light" placeholder="081234567890" style="min-height: 42px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold" style="min-height: 40px;">Simpan Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
