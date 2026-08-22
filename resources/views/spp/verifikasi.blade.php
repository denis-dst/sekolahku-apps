@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran SPP - SekolahKu')
@section('page_title', 'Verifikasi Pembayaran SPP')

@section('content')
<div class="card-custom p-3 p-sm-4 mb-4 bg-white">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
        <div>
            <h5 class="fw-bold m-0 text-dark"><i class="bi bi-shield-check me-2 text-primary"></i>Antrean Verifikasi Bukti Bayar SPP</h5>
            <small class="text-muted">Periksa kesesuaian resi transfer dan setujui status lunas siswa</small>
        </div>
        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-2 fw-semibold">
            <i class="bi bi-hourglass-split me-1"></i> Pending: {{ $pendingPayments->count() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Siswa</th>
                    <th>Periode SPP</th>
                    <th>Tanggal Upload</th>
                    <th>Metode & Nominal</th>
                    <th>Bukti Resi Transfer</th>
                    <th class="text-center" style="width: 180px;">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPayments as $idx => $p)
                    <tr>
                        <td class="text-muted fw-semibold">{{ $idx + 1 }}</td>
                        <td class="fw-semibold text-dark">{{ $p->siswa->nama_lengkap ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border rounded-2">{{ $p->tagihanSpp->bulan }} {{ $p->tagihanSpp->tahun }}</span></td>
                        <td class="text-nowrap small text-muted">{{ $p->created_at->format('d/m/Y H:i') }} WIB</td>
                        <td>
                            <div class="fw-bold text-dark text-nowrap">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</div>
                            <small class="text-muted">{{ $p->metode_pembayaran }}</small>
                        </td>
                        <td>
                            @if($p->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $p->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-2 px-2.5 py-1 text-nowrap" style="min-height: 34px;">
                                    <i class="bi bi-image me-1"></i> Buka Foto Resi
                                </a>
                            @else
                                <span class="text-muted small">Tidak Ada File</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1.5 justify-content-center">
                                <!-- Approve Form -->
                                <form action="{{ route('spp.verifikasi.store', $p->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status_verifikasi" value="Approved">
                                    <button type="submit" class="btn btn-sm btn-success rounded-2 fw-semibold px-2.5 py-1 text-nowrap" style="min-height: 34px;" onclick="return confirm('Setujui pembayaran SPP ini & kirim konfirmasi WA?')">
                                        <i class="bi bi-check-lg me-1"></i> Setujui
                                    </button>
                                </form>

                                <!-- Reject Modal Trigger -->
                                <button class="btn btn-sm btn-outline-danger rounded-2 px-2.5 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}" style="min-height: 34px;">
                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Reject -->
                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Tolak Pembayaran SPP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('spp.verifikasi.store', $p->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status_verifikasi" value="Rejected">
                                    <div class="modal-body p-3 p-sm-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                                            <textarea name="catatan_verifikasi" class="form-control bg-light" rows="3" placeholder="Contoh: Bukti transfer buram/nominal tidak sesuai" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal" style="min-height: 40px;">Batal</button>
                                        <button type="submit" class="btn btn-danger rounded-3 px-4 fw-bold" style="min-height: 40px;">Konfirmasi Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Tidak ada antrean verifikasi pembayaran SPP. Semua bersih!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
