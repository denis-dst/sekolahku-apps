@extends('layouts.app')

@section('title', 'Edit Pengeluaran Talangan - SekolahKu')
@section('page_title', 'Edit Pengeluaran Talangan BOSP')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-7">
        <div class="card-custom p-3 p-sm-4 bg-white">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-light rounded-2 border d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Kembali">
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
                    <label for="expense_category_id" class="form-label fw-semibold text-secondary small">Kategori BOSP <span class="text-danger">*</span></label>
                    <select name="expense_category_id" id="expense_category_id" class="form-select bg-light" required style="min-height: 42px;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('expense_category_id', $expense->expense_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }} ({{ $cat->kode_bosp ?: 'BOSP' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="tanggal" class="form-label fw-semibold text-secondary small">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control bg-light" value="{{ old('tanggal', $expense->tanggal->format('Y-m-d')) }}" required style="min-height: 42px;">
                    </div>

                    <div class="col-12 col-sm-6">
                        <label for="nominal" class="form-label fw-semibold text-secondary small">Nominal Talangan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="text" inputmode="numeric" name="nominal" id="nominal" class="form-control bg-light fw-bold rupiah-input" value="{{ old('nominal', number_format($expense->nominal, 0, ',', '.')) }}" required autocomplete="off" style="min-height: 42px;">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                            <i class="bi bi-info-circle me-1"></i>Hanya angka tanpa simbol dan huruf
                        </small>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="uraian" class="form-label fw-semibold text-secondary small">Uraian / Keperluan <span class="text-danger">*</span></label>
                    <textarea name="uraian" id="uraian" rows="2" class="form-control bg-light" placeholder="Contoh: Beli Kertas HVS & Tinta Printer" required>{{ old('uraian', $expense->uraian) }}</textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="toko_vendor" class="form-label fw-semibold text-secondary small">Nama Toko / Vendor</label>
                        <input type="text" name="toko_vendor" id="toko_vendor" class="form-control bg-light" placeholder="Contoh: Fotocopy Sejahtera" value="{{ old('toko_vendor', $expense->toko_vendor) }}" style="min-height: 42px;">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="lokasi" class="form-label fw-semibold text-secondary small">Lokasi / Kota</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control bg-light" placeholder="Contoh: Sukajadi" value="{{ old('lokasi', $expense->lokasi) }}" style="min-height: 42px;">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small">Tambah Foto Bukti Nota / Resi Tambahan</label>
                    <input type="file" name="receipts[]" class="form-control bg-light" accept="image/*,application/pdf" multiple style="min-height: 42px;">
                    <small class="text-muted">Format didukung: JPG, PNG, HEIC, PDF (Max 5MB)</small>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-light border py-2 px-4 rounded-3 text-muted fw-semibold d-flex align-items-center justify-content-center" style="min-height: 44px;">Batal</a>
                    <button type="submit" class="btn btn-primary flex-fill py-2 rounded-3 fw-bold shadow-xs d-flex align-items-center justify-content-center" style="min-height: 44px;">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatRupiah(value) {
            let numberString = value.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        document.querySelectorAll('.rupiah-input').forEach(function(input) {
            input.addEventListener('input', function() {
                this.value = formatRupiah(this.value);
            });
        });
    });
</script>
@endsection
