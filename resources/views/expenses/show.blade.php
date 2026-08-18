@extends('layouts.app')

@section('title', 'Detail Talangan BOSP - SekolahKu')
@section('page_title', 'Detail Transaksi Talangan & Reimbursement')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('expenses.index') }}" class="btn btn-light border rounded-3 px-3 fw-semibold d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Talangan
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline-secondary rounded-3 px-3 fw-semibold">
            <i class="bi bi-pencil me-1"></i> Edit Data
        </a>
        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan talangan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger rounded-3 px-3 fw-semibold">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Details & Receipts -->
    <div class="col-12 col-lg-7">
        <!-- Main Transaction Detail Card -->
        <div class="card-custom p-4 mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 mb-2 fw-semibold">
                        <i class="bi bi-tag-fill me-1"></i> {{ $expense->category->nama_kategori ?? 'Kategori BOSP' }} ({{ $expense->category->kode_bosp ?: 'BOSP' }})
                    </span>
                    <h4 class="fw-bold text-dark mb-1">{{ $expense->uraian }}</h4>
                    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i> Tanggal Transaksi: <strong>{{ $expense->tanggal->translatedFormat('d F Y') }}</strong></span>
                </div>
                <div class="text-sm-end">
                    <span class="text-muted small d-block">Nominal Talangan</span>
                    <h3 class="fw-bold text-success mb-0">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</h3>
                </div>
            </div>

            <hr class="my-3">

            <div class="row g-3 mb-2">
                <div class="col-6 col-sm-4">
                    <span class="text-muted small d-block">Toko / Vendor</span>
                    <span class="fw-semibold text-dark">{{ $expense->toko_vendor ?: '-' }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <span class="text-muted small d-block">Lokasi</span>
                    <span class="fw-semibold text-dark">{{ $expense->lokasi ?: '-' }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <span class="text-muted small d-block">Sumber Dana</span>
                    <span class="badge bg-secondary-subtle text-dark border">Talangan Pribadi</span>
                </div>
                <div class="col-6 col-sm-4">
                    <span class="text-muted small d-block">Pencatat / Pengaju</span>
                    <span class="fw-semibold text-dark">{{ $expense->user->name ?? '-' }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <span class="text-muted small d-block">Waktu Input</span>
                    <span class="text-dark small">{{ $expense->created_at->translatedFormat('d M Y H:i') }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <span class="text-muted small d-block">Status Saat Ini</span>
                    @php $badge = $expense->status_badge; @endphp
                    <span class="badge {{ $badge['class'] }} rounded-pill px-2.5 py-1">
                        <i class="bi {{ $badge['icon'] }} me-1"></i> {{ $expense->status }}
                    </span>
                </div>
            </div>

            @if($expense->reimbursement)
                <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle mt-3">
                    <div class="d-flex align-items-center gap-2 text-success fw-bold mb-1">
                        <i class="bi bi-check-circle-fill"></i> Data Pencairan Reimbursement
                    </div>
                    <div class="small text-dark">
                        Dicairkan tanggal: <strong>{{ \Carbon\Carbon::parse($expense->reimbursement->tanggal_pencairan)->translatedFormat('d F Y') }}</strong> | Metode: <strong>{{ $expense->reimbursement->metode_transfer }}</strong>
                        @if($expense->reimbursement->catatan)
                            <br>Catatan: {{ $expense->reimbursement->catatan }}
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Receipt Attachments Card -->
        <div class="card-custom p-4">
            <h6 class="fw-bold mb-3 text-dark d-flex align-items-center justify-content-between">
                <span><i class="bi bi-paperclip text-primary me-2"></i> Bukti Pembayaran / Nota ({{ $expense->receipts->count() }})</span>
                <span class="badge bg-light text-muted fw-normal">Lampiran Nota Transaksi</span>
            </h6>

            <div class="row g-3">
                @forelse($expense->receipts as $receipt)
                    <div class="col-6 col-sm-4">
                        <div class="border rounded-3 p-2 bg-light text-center h-100 position-relative shadow-sm">
                            @if($receipt->file_type === 'pdf')
                                <div class="py-4">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3rem;"></i>
                                    <small class="d-block text-truncate mt-2 fw-semibold text-dark">{{ $receipt->file_name ?? 'Dokumen PDF' }}</small>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $receipt->file_path) }}" class="img-fluid rounded-2 mb-2 object-fit-cover w-100" style="height: 140px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $receipt->id }}">
                            @endif

                            <div class="d-flex gap-1 mt-1">
                                <a href="{{ asset('storage/' . $receipt->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100" style="font-size: 0.75rem;">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka File
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Image Preview Modal -->
                    <div class="modal fade" id="receiptModal{{ $receipt->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header">
                                    <h6 class="modal-title fw-bold">Bukti Nota - {{ $expense->uraian }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center bg-dark p-3 rounded-bottom-4">
                                    <img src="{{ asset('storage/' . $receipt->file_path) }}" class="img-fluid rounded shadow" style="max-height: 80vh;">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4 text-center text-muted">
                        <i class="bi bi-camera-slash fs-2 d-block mb-1 text-secondary"></i>
                        Belum ada lampiran bukti nota / struk untuk transaksi ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Status Update & Audit Timeline -->
    <div class="col-12 col-lg-5">
        <!-- Update Status Card -->
        @hasanyrole('Superadmin|School Admin|Bendahara')
            <div class="card-custom p-4 mb-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-arrow-repeat text-primary me-2"></i> Proses Status Reimbursement</h6>

                <form action="{{ route('expenses.update-status', $expense->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Ubah Status Ke:</label>
                        <select name="status" class="form-select bg-light" required>
                            <option value="Belum Diajukan" {{ $expense->status == 'Belum Diajukan' ? 'selected' : '' }}>Belum Diajukan (Talangan Aktif)</option>
                            <option value="Diajukan" {{ $expense->status == 'Diajukan' ? 'selected' : '' }}>Diajukan (Klaim ke BOSP)</option>
                            <option value="Disetujui" {{ $expense->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (Siap Dicairkan)</option>
                            <option value="Dibayar" {{ $expense->status == 'Dibayar' ? 'selected' : '' }}>Dibayar (Reimburse Selesai)</option>
                            <option value="Ditolak" {{ $expense->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Catatan / Alasan Perubahan</label>
                        <input type="text" name="catatan" class="form-control bg-light" placeholder="Contoh: Disetujui pencairan tahap 1 BOSP">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">
                        <i class="bi bi-check2-circle me-1"></i> Perbarui Status
                    </button>
                </form>
            </div>
        @endhasanyrole

        <!-- History Audit Timeline Card -->
        <div class="card-custom p-4">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-clock-history text-primary me-2"></i> Histori Timeline Status</h6>

            <ul class="list-unstyled mb-0 position-relative ps-3" style="border-left: 2px solid #dcfce7;">
                @forelse($expense->statusHistories()->latest()->get() as $history)
                    <li class="mb-3 position-relative">
                        <div class="position-absolute" style="left: -23px; top: 2px; width: 14px; height: 14px; border-radius: 50%; background: #16a34a; border: 2px solid #ffffff; box-shadow: 0 0 0 2px #dcfce7;"></div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                            {{ $history->status_sesudah }}
                        </div>
                        <small class="text-muted d-block">
                            {{ $history->created_at->translatedFormat('d M Y H:i') }} oleh <strong>{{ $history->user->name ?? 'Sistem' }}</strong>
                        </small>
                        @if($history->catatan)
                            <div class="small text-secondary bg-light p-2 rounded-2 mt-1 border">
                                {{ $history->catatan }}
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="text-muted small">Belum ada riwayat perubahan status.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
