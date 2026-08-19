@extends('layouts.app')

@section('title', 'Tagihan & Pembayaran SPP - SekolahKu')
@section('page_title', 'Tagihan & SPP Siswa')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="bi bi-wallet2 me-2 text-primary"></i>Daftar Tagihan SPP</h5>
        @hasanyrole('Superadmin|School Admin|Bendahara')
            <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#generateSppModal">
                <i class="bi bi-plus-circle me-1"></i> Buat Tagihan SPP Massal
            </button>
        @endhasanyrole
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Periode</th>
                    <th>Nominal Tagihan</th>
                    <th>Status Pembayaran</th>
                    <th>Jatuh Tempo</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $idx => $t)
                    <tr>
                        <td>{{ $tagihans->firstItem() + $idx }}</td>
                        <td class="fw-semibold">{{ $t->siswa->nama_lengkap }}</td>
                        <td>{{ $t->bulan }} {{ $t->tahun }}</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                        <td>
                            @if($t->status == 'Lunas')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Lunas</span>
                            @elseif($t->status == 'Menunggu Verifikasi')
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Belum Lunas</span>
                            @endif
                        </td>
                        <td>{{ $t->jatuh_tempo ? $t->jatuh_tempo->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            @if($t->status != 'Lunas')
                                <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#payModal{{ $t->id }}">
                                    <i class="bi bi-qr-code me-1"></i> Bayar / Upload Bukti
                                </button>
                            @else
                                <span class="text-muted small"><i class="bi bi-patch-check-fill text-success fs-5"></i> Lunas</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Payment QRIS & Upload Proof -->
                    <div class="modal fade" id="payModal{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Pembayaran SPP - {{ $t->bulan }} {{ $t->tahun }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 text-center">
                                    <div class="p-3 bg-light rounded-3 mb-3 border">
                                        <div class="small text-muted">Total Tagihan SPP</div>
                                        <h3 class="fw-extrabold text-primary m-0">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</h3>
                                        <div class="small text-muted mt-1">Siswa: {{ $t->siswa->nama_lengkap }}</div>
                                    </div>

                                    <!-- QRIS Code Image Display -->
                                    <div class="mb-3">
                                        <div class="fw-bold small text-uppercase text-muted mb-2">SCAN QRIS PEMBAYARAN SEKOLAH</div>
                                        @if($school->qris_image)
                                            <div class="p-2 border rounded-3 bg-white d-inline-block shadow-sm">
                                                <img src="{{ asset('storage/' . $school->qris_image) }}" alt="QRIS Sekolah" class="img-fluid rounded" style="max-height: 220px;">
                                            </div>
                                        @else
                                            <div class="alert alert-warning py-2 small">Barcode QRIS sekolah belum diunggah oleh admin. Silakan transfer via bank.</div>
                                        @endif
                                    </div>

                                    <!-- Bank Account Numbers -->
                                    @if($school->bank_accounts && count($school->bank_accounts) > 0)
                                        <div class="mb-4 text-start bg-light p-3 rounded-3 border">
                                            <div class="fw-bold small text-uppercase text-muted mb-1">REKENING TRANSFER BANK:</div>
                                            @foreach($school->bank_accounts as $bank)
                                                <div class="small fw-semibold text-dark mb-1">
                                                    <i class="bi bi-bank me-1"></i> {{ $bank['bank'] }}: <strong>{{ $bank['account_number'] }}</strong> (a.n {{ $bank['account_name'] }})
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Upload Proof Form -->
                                    <form action="{{ route('spp.upload-bukti', $t->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-semibold">Metode Pembayaran</label>
                                            <select name="metode_pembayaran" class="form-select">
                                                <option value="Manual QRIS">Scan Manual QRIS</option>
                                                <option value="Transfer Bank">Transfer Bank</option>
                                                <option value="Cash">Setor Tunai (Cash)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label small fw-semibold">Nominal Yang Dibayar</label>
                                            <input type="number" name="nominal_bayar" class="form-control" value="{{ (int)$t->total_tagihan }}" required>
                                        </div>
                                        <div class="mb-4 text-start">
                                            <label class="form-label small fw-semibold">Foto Bukti Transfer / Resi Nota</label>
                                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                                            <small class="text-muted" style="font-size:0.75rem;">Upload foto resi/struk/bukti transfer dari HP Anda.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm">
                                            <i class="bi bi-upload me-1"></i> Unggah Bukti Pembayaran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data tagihan SPP.</td>
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
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Buat Tagihan SPP Bulanan Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('spp.generate') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select name="bulan" class="form-select">
                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $m)
                                <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal SPP (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="150000" value="150000" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Generate Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endhasanyrole
@endsection
