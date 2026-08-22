@extends('layouts.app')

@section('title', 'Tagihan & Pembayaran SPP - SekolahKu')
@section('page_title', 'Tagihan & SPP Siswa')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2.5 mb-3">
        <div>
            <h5 class="fw-bold m-0 text-dark"><i class="bi bi-wallet2 me-2 text-primary"></i>Daftar Tagihan SPP</h5>
            <small class="text-muted">Kelola penerbitan tagihan bulanan dan status pelunasan siswa</small>
        </div>
        @hasanyrole('Superadmin|School Admin|Bendahara')
            <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold d-flex align-items-center justify-content-center gap-1 w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#generateSppModal" style="min-height: 40px;">
                <i class="bi bi-plus-circle me-1"></i> Buat Tagihan SPP Massal
            </button>
        @endhasanyrole
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Periode</th>
                    <th>Nominal Tagihan</th>
                    <th>Status Pembayaran</th>
                    <th>Jatuh Tempo</th>
                    <th class="text-center" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $idx => $t)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $tagihans->firstItem() + $idx }}</td>
                        <td class="fw-semibold text-dark">{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border rounded-2">{{ $t->bulan }} {{ $t->tahun }}</span></td>
                        <td class="fw-bold text-dark text-nowrap">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status == 'Lunas')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-2.5 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Lunas
                                </span>
                            @elseif($t->status == 'Menunggu Verifikasi')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-2 px-2.5 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-2 px-2.5 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="text-nowrap">{{ $t->jatuh_tempo ? $t->jatuh_tempo->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            @if($t->status != 'Lunas')
                                <button class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#payModal{{ $t->id }}" style="min-height: 36px;">
                                    <i class="bi bi-qr-code me-1"></i> Bayar / Bukti
                                </button>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-2 px-2.5 py-1">
                                    <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi
                                </span>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Payment QRIS & Upload Proof -->
                    <div class="modal fade" id="payModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark">Pembayaran SPP - {{ $t->bulan }} {{ $t->tahun }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-3 p-sm-4 text-center">
                                    <div class="p-3 bg-light rounded-3 mb-3 border">
                                        <div class="small text-muted">Total Tagihan SPP</div>
                                        <h3 class="fw-bold text-primary m-0" style="font-size: 1.5rem;">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</h3>
                                        <div class="small text-muted mt-1">Siswa: <strong>{{ $t->siswa->nama_lengkap }}</strong></div>
                                    </div>

                                    <!-- QRIS Code Image Display -->
                                    <div class="mb-3">
                                        <div class="fw-bold small text-uppercase text-muted mb-2" style="letter-spacing: 0.5px;">SCAN QRIS PEMBAYARAN SEKOLAH</div>
                                        @if($school->qris_image)
                                            <div class="p-2 border rounded-3 bg-white d-inline-block shadow-xs">
                                                <img src="{{ asset('storage/' . $school->qris_image) }}" alt="QRIS Sekolah" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        @else
                                            <div class="alert alert-warning py-2 small rounded-3">Barcode QRIS sekolah belum diunggah oleh admin. Silakan transfer via bank.</div>
                                        @endif
                                    </div>

                                    <!-- Bank Account Numbers -->
                                    @if($school->bank_accounts && count($school->bank_accounts) > 0)
                                        <div class="mb-3 text-start bg-light-subtle p-3 rounded-3 border">
                                            <div class="fw-bold small text-uppercase text-muted mb-1">REKENING TRANSFER BANK:</div>
                                            @foreach($school->bank_accounts as $bank)
                                                <div class="small fw-semibold text-dark mb-1">
                                                    <i class="bi bi-bank me-1 text-primary"></i> {{ $bank['bank'] }}: <strong>{{ $bank['account_number'] }}</strong> (a.n {{ $bank['account_name'] }})
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Upload Proof Form -->
                                    <form action="{{ route('spp.upload-bukti', $t->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                                            <select name="metode_pembayaran" class="form-select bg-light" style="min-height: 42px;">
                                                <option value="Manual QRIS">Scan Manual QRIS</option>
                                                <option value="Transfer Bank">Transfer Bank</option>
                                                <option value="Cash">Setor Tunai (Cash)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-semibold">Nominal Yang Dibayar <span class="text-danger">*</span></label>
                                            <input type="number" name="nominal_bayar" class="form-control bg-light fw-bold" value="{{ (int)$t->total_tagihan }}" required style="min-height: 42px;">
                                        </div>
                                        <div class="mb-4 text-start">
                                            <label class="form-label small fw-semibold">Foto Bukti Transfer / Resi Nota <span class="text-danger">*</span></label>
                                            <input type="file" name="bukti_pembayaran" class="form-control bg-light" accept="image/*" required style="min-height: 42px;">
                                            <small class="text-muted" style="font-size:0.75rem;">Upload foto resi/struk/bukti transfer dari HP Anda.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-xs" style="min-height: 44px;">
                                            <i class="bi bi-upload me-1"></i> Unggah Bukti Pembayaran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Belum ada data tagihan SPP.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $tagihans->links() }}
    </div>
</div>

<!-- Modal Generate SPP Massal -->
@hasanyrole('Superadmin|School Admin|Bendahara')
<div class="modal fade" id="generateSppModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Buat Tagihan SPP Bulanan Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('spp.generate') }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bulan <span class="text-danger">*</span></label>
                        <select name="bulan" class="form-select bg-light" style="min-height: 42px;">
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $m)
                                <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control bg-light" value="{{ date('Y') }}" required style="min-height: 42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal SPP (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" class="form-control bg-light fw-bold" placeholder="150000" value="150000" required style="min-height: 42px;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold" style="min-height: 40px;">Generate Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endhasanyrole
@endsection
