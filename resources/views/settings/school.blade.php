@extends('layouts.app')

@section('title', 'Profil Sekolah & QRIS - SekolahKu')
@section('page_title', 'Profil Sekolah & QRIS')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card-custom p-4 p-md-5">
            <h4 class="fw-bold mb-4"><i class="bi bi-gear-wide-connected text-primary me-2"></i>Pengaturan Sekolah & Pembayaran QRIS</h4>

            <form action="{{ route('settings.school.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4 mb-4">
                    <!-- General School Info -->
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3">Informasi Umum Sekolah</h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Sekolah</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $school->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">NPSN</label>
                            <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $school->npsn) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenjang Pendidikan</label>
                            <select name="jenjang" class="form-select">
                                <option value="PAUD/TK/RA" {{ $school->jenjang == 'PAUD/TK/RA' ? 'selected' : '' }}>PAUD/TK/RA</option>
                                <option value="SD/MI" {{ $school->jenjang == 'SD/MI' ? 'selected' : '' }}>SD/MI</option>
                                <option value="SMP/MTs" {{ $school->jenjang == 'SMP/MTs' ? 'selected' : '' }}>SMP/MTs</option>
                                <option value="SMA/SMK/MA" {{ $school->jenjang == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA/SMK/MA</option>
                                <option value="Pesantren" {{ $school->jenjang == 'Pesantren' ? 'selected' : '' }}>Pesantren</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $school->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. HP / Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone) }}">
                        </div>
                    </div>

                    <!-- Payment QRIS & Bank Settings -->
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3">Upload QRIS Barcode & Bank Details</h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary"><i class="bi bi-qr-code-scan me-1"></i>Gambar QRIS Statis Sekolah</label>
                            <input type="file" name="qris_image" class="form-control" accept="image/*">
                            @if($school->qris_image)
                                <div class="mt-2 p-2 border rounded text-center bg-light">
                                    <img src="{{ asset('storage/' . $school->qris_image) }}" alt="QRIS Sekolah" class="img-fluid rounded" style="max-height: 180px;">
                                    <div class="small text-muted mt-1">QRIS Aktif Saat Ini</div>
                                </div>
                            @endif
                            <small class="text-muted d-block mt-1">Upload gambar barcode QRIS sekolah Anda untuk ditampilkan pada halaman invoice SPP orang tua.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rekening Bank 1</label>
                            <div class="row g-2">
                                <div class="col-4"><input type="text" name="bank_name_1" class="form-control" placeholder="Nama Bank" value="{{ $school->bank_accounts[0]['bank'] ?? '' }}"></div>
                                <div class="col-4"><input type="text" name="bank_acc_1" class="form-control" placeholder="No. Rekening" value="{{ $school->bank_accounts[0]['account_number'] ?? '' }}"></div>
                                <div class="col-4"><input type="text" name="bank_holder_1" class="form-control" placeholder="Atas Nama" value="{{ $school->bank_accounts[0]['account_name'] ?? '' }}"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-whatsapp me-1 text-success"></i> WhatsApp WAHA API Token / Key</label>
                            <input type="text" name="fonnte_token" class="form-control" placeholder="Masukkan API Key WAHA (Opsional)..." value="{{ old('fonnte_token', $school->fonnte_token) }}">
                            <small class="text-muted">Digunakan untuk otorisasi pengiriman notifikasi presensi & SPP via WAHA HTTP API.</small>
                        </div>
                    </div>
                </div>

                <!-- Signatures Header Info -->
                <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3">Pejabat Pengesahan (Tanda Tangan Report)</h6>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Nama Kepala Sekolah</label>
                        <input type="text" name="kepala_sekolah_nama" class="form-control" value="{{ old('kepala_sekolah_nama', $school->kepala_sekolah_nama) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">NIP Kepala Sekolah</label>
                        <input type="text" name="kepala_sekolah_nip" class="form-control" value="{{ old('kepala_sekolah_nip', $school->kepala_sekolah_nip) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Nama Bendahara Sekolah</label>
                        <input type="text" name="bendahara_nama" class="form-control" value="{{ old('bendahara_nama', $school->bendahara_nama) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">NIP Bendahara Sekolah</label>
                        <input type="text" name="bendahara_nip" class="form-control" value="{{ old('bendahara_nip', $school->bendahara_nip) }}">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
