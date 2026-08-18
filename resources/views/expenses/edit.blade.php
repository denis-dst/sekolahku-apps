@extends('layouts.app')

@section('title', 'Edit Pengeluaran Talangan - SekolahKu')
@section('page_title', 'Edit Pengeluaran Talangan BOSP')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-7">
        <div class="card-custom p-4">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Edit Data Pengeluaran Talangan</h5>
                    <small class="text-muted">Perbarui rincian transaksi talangan atau tambahkan bukti nota baru</small>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger rounded-3 py-2 px-3 mb-4 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="expense_category_id" class="form-label fw-semibold text-secondary">Kategori BOSP <span class="text-danger">*</span></label>
                    <select name="expense_category_id" id="expense_category_id" class="form-select bg-light" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('expense_category_id', $expense->expense_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }} ({{ $cat->kode_bosp ?: 'BOSP' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="tanggal" class="form-label fw-semibold text-secondary">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control bg-light" value="{{ old('tanggal', $expense->tanggal->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-12 col-sm-6">
                        <label for="nominal" class="form-label fw-semibold text-secondary">Nominal Talangan (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" id="nominal" class="form-control bg-light fw-bold" value="{{ old('nominal', $expense->nominal) }}" required min="1" step="100">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="uraian" class="form-label fw-semibold text-secondary">Uraian / Keperluan <span class="text-danger">*</span></label>
                    <textarea name="uraian" id="uraian" rows="2" class="form-control bg-light" placeholder="Contoh: Beli Kertas HVS & Tinta Printer" required>{{ old('uraian', $expense->uraian) }}</textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="toko_vendor" class="form-label fw-semibold text-secondary">Nama Toko / Vendor</label>
                        <input type="text" name="toko_vendor" id="toko_vendor" class="form-control bg-light" placeholder="Contoh: Fotocopy Sejahtera" value="{{ old('toko_vendor', $expense->toko_vendor) }}">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="lokasi" class="form-label fw-semibold text-secondary">Lokasi / Kota</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control bg-light" placeholder="Contoh: Sukajadi" value="{{ old('lokasi', $expense->lokasi) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary">Tambah Foto Bukti Nota / Resi Tambahan</label>
                    <input type="file" name="receipts[]" class="form-control bg-light" accept="image/*,application/pdf" multiple>
                    <small class="text-muted">Format didukung: JPG, PNG, HEIC, PDF (Max 5MB)</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-light border py-2 px-4 rounded-3 text-muted fw-semibold">Batal</a>
                    <button type="submit" class="btn btn-primary flex-fill py-2 rounded-3 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
