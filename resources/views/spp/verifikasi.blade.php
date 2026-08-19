@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran SPP - SekolahKu')
@section('page_title', 'Verifikasi Pembayaran SPP')

@section('content')
<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0"><i class="bi bi-shield-check me-2 text-primary"></i>Antrean Verifikasi Bukti Bayar SPP</h5>
        <span class="badge bg-warning text-dark px-3 py-2 fs-6">Pending: {{ $pendingPayments->count() }}</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Periode SPP</th>
                    <th>Tanggal Upload</th>
                    <th>Metode & Nominal</th>
                    <th>Bukti Resi Transfer</th>
                    <th class="text-center">Aksi Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPayments as $idx => $p)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="fw-semibold">{{ $p->siswa->nama_lengkap }}</td>
                        <td>{{ $p->tagihanSpp->bulan }} {{ $p->tagihanSpp->tahun }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }} WIB</td>
                        <td>
                            <div class="fw-bold text-success">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</div>
                            <small class="text-muted">{{ $p->metode_pembayaran }}</small>
                        </td>
                        <td>
                            @if($p->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $p->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-2">
                                    <i class="bi bi-image me-1"></i> Lihat Foto Resi
                                </a>
                            @else
                                <span class="text-muted small">Tidak Ada File</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <!-- Approve Form -->
                                <form action="{{ route('spp.verifikasi.store', $p->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status_verifikasi" value="Approved">
                                    <button type="submit" class="btn btn-sm btn-success rounded-3 fw-bold" onclick="return confirm('Setujui pembayaran SPP ini & kirim konfirmasi WA?')">
                                        <i class="bi bi-check-lg me-1"></i> Setujui (Lunas)
                                    </button>
                                </form>

                                <!-- Reject Modal Trigger -->
                                <button class="btn btn-sm btn-outline-danger rounded-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">
                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Reject -->
                    <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-danger">Tolak Pembayaran SPP</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('spp.verifikasi.store', $p->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status_verifikasi" value="Rejected">
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Alasan Penolakan</label>
                                            <textarea name="catatan_verifikasi" class="form-control" rows="3" placeholder="Contoh: Bukti transfer buram/nominal tidak sesuai" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger rounded-3 px-4 fw-bold">Konfirmasi Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada antrean verifikasi pembayaran SPP. Semua bersih!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
