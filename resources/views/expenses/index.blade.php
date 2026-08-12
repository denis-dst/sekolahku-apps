@extends('layouts.app')

@section('title', 'BendaharaKu & LPJ BOSP - SekolahKu')
@section('page_title', 'BendaharaKu - Asisten Digital Finance & LPJ BOSP')

@section('content')
<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card-custom p-3 border-start border-primary border-4">
            <span class="text-muted small fw-semibold text-uppercase">Total Talangan Dicatat</span>
            <h4 class="fw-bold m-0 text-dark">Rp {{ number_format($totalTalangan, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-custom p-3 border-start border-warning border-4">
            <span class="text-muted small fw-semibold text-uppercase">Belum Diganti (Pending)</span>
            <h4 class="fw-bold m-0 text-warning">Rp {{ number_format($totalPending, 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-custom p-3 border-start border-success border-4">
            <span class="text-muted small fw-semibold text-uppercase">Sudah Direimburse (Dibayar)</span>
            <h4 class="fw-bold m-0 text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0"><i class="bi bi-cash-stack me-2 text-primary"></i>Pencatatan Talangan Pribadi BOSP</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('expenses.export-pdf') }}" class="btn btn-outline-danger btn-sm rounded-3 px-3 fw-semibold">
                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Rekap LPJ BOSP (PDF)
            </a>
            <button class="btn btn-primary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#newExpenseModal">
                <i class="bi bi-plus-lg me-1"></i> Input Talangan (< 30 dtk)
            </button>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Pengaju</th>
                    <th>Kategori BOSP</th>
                    <th>Uraian Keperluan</th>
                    <th>Nominal</th>
                    <th>Bukti Nota</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                    <tr>
                        <td>{{ $e->tanggal->format('d/m/Y') }}</td>
                        <td><span class="fw-semibold">{{ $e->user->name }}</span></td>
                        <td><span class="badge bg-secondary-subtle text-dark border">{{ $e->category->nama_kategori }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $e->uraian }}</div>
                            <small class="text-muted">{{ $e->toko_vendor ?: 'Vendor N/A' }}</small>
                        </td>
                        <td class="fw-bold text-dark">Rp {{ number_format($e->nominal, 0, ',', '.') }}</td>
                        <td>
                            @if($e->receipts->count() > 0)
                                <button class="btn btn-sm btn-outline-secondary rounded-2" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $e->id }}">
                                    <i class="bi bi-paperclip me-1"></i> {{ $e->receipts->count() }} Bukti
                                </button>
                            @else
                                <span class="text-muted small">Tanpa Nota</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeColor = match($e->status) {
                                    'Dibayar' => 'success',
                                    'Disetujui' => 'info',
                                    'Diajukan' => 'warning',
                                    'Ditolak' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }}">{{ $e->status }}</span>
                        </td>
                        <td class="text-center">
                            @hasanyrole('Superadmin|School Admin|Bendahara')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Proses
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Diajukan">
                                                <button type="submit" class="dropdown-item">Ajukan Claim</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Disetujui">
                                                <button type="submit" class="dropdown-item text-info">Setujui Claim</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Dibayar">
                                                <button type="submit" class="dropdown-item text-success fw-bold">Cairkan Reimburse (Dibayar)</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('expenses.update-status', $e->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" class="dropdown-item text-danger">Tolak Claim</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endhasanyrole
                        </td>
                    </tr>

                    <!-- Modal Receipt Viewer -->
                    @if($e->receipts->count() > 0)
                    <div class="modal fade" id="receiptModal{{ $e->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Bukti Nota - {{ $e->uraian }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4 text-center">
                                    @foreach($e->receipts as $r)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $r->file_path) }}" class="img-fluid rounded border shadow-sm" style="max-height: 350px;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada pengeluaran talangan. Klik "Input Talangan" untuk mencatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $expenses->links() }}
    </div>
</div>

<!-- Modal Fast Entry Expense (<30s) -->
<div class="modal fade" id="newExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-lightning-fill text-warning me-1"></i>Input Talangan Pribadi (&lt;30 Detik)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Pengeluaran BOSP</label>
                        <select name="expense_category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }} ({{ $cat->kode_bosp ?: 'BOSP' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal Pengeluaran (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="50000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Uraian Keperluan</label>
                        <input type="text" name="uraian" class="form-control" placeholder="Contoh: Beli Kertas HVS 2 Rim" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Toko / Vendor</label>
                        <input type="text" name="toko_vendor" class="form-control" placeholder="Contoh: Fotocopy Barokah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Struk / Nota (Multi-Upload)</label>
                        <input type="file" name="receipts[]" class="form-control" accept="image/*,application/pdf" multiple>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Talangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
