@extends('layouts.app')

@section('title', 'Data Guru - SekolahKu')
@section('page_title', 'Data Guru & Pendidik')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="bi bi-person-badge-fill me-2 text-primary"></i>Daftar Guru & Staff Pendidik</h5>
        <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newGuruModal">
            <i class="bi bi-person-plus me-1"></i> Tambah Guru Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap & Gelar</th>
                    <th>NIP / NUPTK</th>
                    <th>Email Account</th>
                    <th>No. HP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $idx => $g)
                    <tr>
                        <td>{{ $gurus->firstItem() + $idx }}</td>
                        <td class="fw-semibold">{{ $g->nama_lengkap }}</td>
                        <td>{{ $g->nip ?: '-' }} / {{ $g->nuptk ?: '-' }}</td>
                        <td>{{ $g->user->email ?? '-' }}</td>
                        <td><i class="bi bi-whatsapp text-success me-1"></i> {{ $g->no_hp ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data guru terdaftar.</td>
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
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Guru & Akun Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nurhayati, S.Pd." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Email (Login)</label>
                        <input type="email" name="email" class="form-control" placeholder="guru@sekolah.sch.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kata Sandi</label>
                        <input type="password" name="password" class="form-control" placeholder="Default: password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIP</label>
                        <input type="text" name="nip" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. HP WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Guru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
