@extends('layouts.app')

@section('title', 'Kategori Belanja BOSP - SekolahKu')
@section('page_title', 'Kategori Belanja BOSP')

@section('content')
<div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mb-4">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('expenses.index') }}" class="btn btn-light border rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2 flex-fill flex-sm-grow-0" style="min-height: 40px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Talangan
        </a>
        <a href="{{ route('expenses.report') }}" class="btn btn-outline-danger rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2 flex-fill flex-sm-grow-0" style="min-height: 40px;">
            <i class="bi bi-file-earmark-pdf"></i> Rekap Periode LPJ
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Add Category Form -->
    <div class="col-12 col-md-5">
        <div class="card-custom p-3 p-sm-4 bg-white">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-plus-circle-fill me-2 text-primary"></i> Tambah Kategori BOSP Baru</h6>

            @if ($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-3 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label fw-semibold text-secondary small">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control bg-light" placeholder="Contoh: Honor Pengajar Tamu / Ekstrakurikuler" required>
                </div>

                <div class="mb-3">
                    <label for="kode_bosp" class="form-label fw-semibold text-secondary small">Kode / Komponen BOSP</label>
                    <input type="text" name="kode_bosp" id="kode_bosp" class="form-control bg-light" placeholder="Contoh: Standar 5 atau BOSP-02">
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="form-label fw-semibold text-secondary small">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control bg-light" placeholder="Penjelasan peruntukan kategori..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">
                    <i class="bi bi-plus-lg me-1"></i> Simpan Kategori BOSP
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Category List Table -->
    <div class="col-12 col-md-7">
        <div class="card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold m-0 text-dark"><i class="bi bi-tags-fill me-2 text-primary"></i> Daftar Kategori BOSP Sekolah</h6>
                <span class="badge bg-light text-muted border">{{ $categories->count() }} Kategori</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        <tr>
                            <th>Kategori & Kode</th>
                            <th>Total Transaksi</th>
                            <th class="text-end" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $cat->nama_kategori }}</div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.75rem;">
                                            {{ $cat->kode_bosp ?: 'BOSP' }}
                                        </span>
                                        @if($cat->keterangan)
                                            <small class="text-muted text-truncate" style="max-width: 250px;">{{ $cat->keterangan }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        {{ $cat->expenses_count }} Transaksi
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cat->id }}" title="Edit Kategori">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        @if($cat->expenses_count == 0)
                                            <form action="{{ route('expenses.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="btn btn-sm btn-light border disabled text-muted" title="Terkunci (Sudah digunakan transaksi)">
                                                <i class="bi bi-lock-fill"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for Category -->
                            <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Kategori BOSP</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('expenses.categories.update', $cat->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-secondary small">Nama Kategori <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_kategori" class="form-control bg-light" value="{{ $cat->nama_kategori }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-secondary small">Kode / Komponen BOSP</label>
                                                    <input type="text" name="kode_bosp" class="form-control bg-light" value="{{ $cat->kode_bosp }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-secondary small">Keterangan</label>
                                                    <textarea name="keterangan" rows="2" class="form-control bg-light">{{ $cat->keterangan }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada kategori BOSP yang dibuat. Silakan tambahkan pada form di samping.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
