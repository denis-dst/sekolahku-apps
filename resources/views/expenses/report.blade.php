@extends('layouts.app')

@section('title', 'Rekap Periode & LPJ BOSP - SekolahKu')
@section('page_title', 'Rekapitulasi Periode & Laporan LPJ BOSP')

@section('content')
<!-- Filter Card -->
<div class="card-custom p-4 mb-4">
    <form action="{{ route('expenses.report') }}" method="GET" class="row g-3 align-items-end" id="filterForm">
        
        <div class="col-12 col-sm-6 col-md-3">
            <label class="form-label fw-semibold text-secondary small">Jenis Periode Rekap</label>
            <select name="filter_type" id="filter_type" class="form-select bg-light" onchange="togglePeriodFields()">
                <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Bulanan</option>
                <option value="quarter" {{ $filterType == 'quarter' ? 'selected' : '' }}>Triwulan (BOSP)</option>
                <option value="semester" {{ $filterType == 'semester' ? 'selected' : '' }}>Semester</option>
                <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Tahunan</option>
                <option value="custom" {{ $filterType == 'custom' ? 'selected' : '' }}>Rentang Tanggal Custom</option>
            </select>
        </div>

        <!-- Filter: Year -->
        <div class="col-6 col-sm-3 col-md-2" id="field_year">
            <label class="form-label fw-semibold text-secondary small">Tahun</label>
            <select name="year" class="form-select bg-light">
                @for($y = date('Y') + 1; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <!-- Filter: Month -->
        <div class="col-6 col-sm-3 col-md-2" id="field_month">
            <label class="form-label fw-semibold text-secondary small">Bulan</label>
            <select name="month" class="form-select bg-light">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter: Quarter -->
        <div class="col-6 col-sm-3 col-md-2" id="field_quarter" style="display: none;">
            <label class="form-label fw-semibold text-secondary small">Triwulan BOSP</label>
            <select name="quarter" class="form-select bg-light">
                <option value="1" {{ $quarter == 1 ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                <option value="2" {{ $quarter == 2 ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                <option value="3" {{ $quarter == 3 ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                <option value="4" {{ $quarter == 4 ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
            </select>
        </div>

        <!-- Filter: Semester -->
        <div class="col-6 col-sm-3 col-md-2" id="field_semester" style="display: none;">
            <label class="form-label fw-semibold text-secondary small">Semester</label>
            <select name="semester" class="form-select bg-light">
                <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Semester I (Jan - Jun)</option>
                <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Semester II (Jul - Des)</option>
            </select>
        </div>

        <!-- Filter: Custom Dates -->
        <div class="col-6 col-sm-3 col-md-2" id="field_date_from" style="display: none;">
            <label class="form-label fw-semibold text-secondary small">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control bg-light" value="{{ request('date_from', date('Y-m-01')) }}">
        </div>
        <div class="col-6 col-sm-3 col-md-2" id="field_date_to" style="display: none;">
            <label class="form-label fw-semibold text-secondary small">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control bg-light" value="{{ request('date_to', date('Y-m-t')) }}">
        </div>

        <!-- Filter: Status -->
        <div class="col-6 col-sm-3 col-md-2">
            <label class="form-label fw-semibold text-secondary small">Filter Status</label>
            <select name="status" class="form-select bg-light">
                <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="Belum Diajukan" {{ $statusFilter == 'Belum Diajukan' ? 'selected' : '' }}>Belum Diajukan</option>
                <option value="Diajukan" {{ $statusFilter == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="Disetujui" {{ $statusFilter == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Dibayar" {{ $statusFilter == 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
                <option value="Ditolak" {{ $statusFilter == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill fw-semibold py-2">
                <i class="bi bi-filter me-1"></i> Tampilkan
            </button>
            <a href="{{ route('expenses.index') }}" class="btn btn-light border py-2 px-3" title="Kembali ke Daftar">
                <i class="bi bi-list-task"></i>
            </a>
        </div>
    </form>
</div>

<!-- Header Summary Card -->
<div class="card-custom p-4 mb-4 border-top border-4 border-primary">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold px-3 py-1.5 mb-2 rounded-pill">
                PERIODE: {{ strtoupper($periodLabel) }}
            </span>
            <h4 class="fw-bold mb-1 text-dark">Rekapitulasi Talangan Pribadi Dana BOSP</h4>
            <span class="text-muted"><i class="bi bi-building me-1"></i> {{ auth()->user()->school->name }} (NPSN: {{ auth()->user()->school->npsn ?? '-' }})</span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="text-md-end">
                <span class="text-muted small d-block">Total Nominal Periode Ini</span>
                <h3 class="fw-bold text-success mb-0">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ $expenses->count() }} Transaksi Tercatat</small>
            </div>
            
            <a href="{{ route('expenses.export-pdf', request()->all()) }}" target="_blank" class="btn btn-danger btn-lg px-4 fw-bold shadow-sm rounded-3 d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-pdf-fill fs-4"></i> Cetak PDF LPJ
            </a>
        </div>
    </div>
</div>

<!-- Category Breakdown Grid -->
<div class="row g-3 mb-4">
    @foreach($categoryTotals as $cat)
        <div class="col-6 col-sm-4 col-md-3">
            <div class="card-custom p-3 border-start border-4 border-success h-100">
                <span class="text-muted small d-block text-truncate fw-semibold">{{ $cat['name'] }}</span>
                <span class="fw-bold fs-6 text-dark d-block mt-1">Rp {{ number_format($cat['total'], 0, ',', '.') }}</span>
                <small class="text-secondary" style="font-size: 0.75rem;">{{ $cat['count'] }} Transaksi</small>
            </div>
        </div>
    @endforeach
</div>

<!-- Itemized Transaction Table -->
<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold m-0 text-dark"><i class="bi bi-table me-2 text-primary"></i> Rincian Pengeluaran LPJ BOSP</h6>
        <span class="badge bg-light text-muted border">{{ $expenses->count() }} Data Ditemukan</span>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <tr>
                    <th style="width: 45px;" class="text-center">No</th>
                    <th style="width: 105px;">Tanggal</th>
                    <th>Pengaju</th>
                    <th>Uraian Keperluan</th>
                    <th>Kategori BOSP</th>
                    <th>Vendor / Toko</th>
                    <th class="text-end" style="width: 140px;">Nominal (Rp)</th>
                    <th style="width: 110px;" class="text-center">Status</th>
                    <th style="width: 60px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $index => $exp)
                    <tr>
                        <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                        <td class="text-nowrap">{{ $exp->tanggal->format('d/m/Y') }}</td>
                        <td><span class="fw-semibold text-dark">{{ $exp->user->name ?? '-' }}</span></td>
                        <td class="fw-semibold text-dark">{{ $exp->uraian }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-dark border" style="font-size: 0.75rem;">
                                {{ $exp->category->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $exp->toko_vendor ?: '-' }}</td>
                        <td class="text-end fw-bold text-dark text-nowrap">
                            {{ number_format($exp->nominal, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @php $badge = $exp->status_badge; @endphp
                            <span class="badge {{ $badge['class'] }} rounded-pill" style="font-size: 0.75rem;">
                                {{ $exp->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('expenses.show', $exp->id) }}" class="btn btn-sm btn-light border" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 text-secondary d-block mb-2"></i>
                            Tidak ada pengeluaran talangan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($expenses->count() > 0)
                <tfoot class="table-group-divider fw-bold bg-light" style="font-size: 0.95rem;">
                    <tr>
                        <td colspan="6" class="text-end text-uppercase">Total Keseluruhan Talangan BOSP:</td>
                        <td class="text-end text-success fs-5">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

@push('scripts')
<script>
function togglePeriodFields() {
    const filterType = document.getElementById('filter_type').value;
    const fieldYear = document.getElementById('field_year');
    const fieldMonth = document.getElementById('field_month');
    const fieldQuarter = document.getElementById('field_quarter');
    const fieldSemester = document.getElementById('field_semester');
    const fieldDateFrom = document.getElementById('field_date_from');
    const fieldDateTo = document.getElementById('field_date_to');

    // Reset visibility
    fieldYear.style.display = (filterType !== 'custom') ? 'block' : 'none';
    fieldMonth.style.display = (filterType === 'month') ? 'block' : 'none';
    fieldQuarter.style.display = (filterType === 'quarter') ? 'block' : 'none';
    fieldSemester.style.display = (filterType === 'semester') ? 'block' : 'none';
    fieldDateFrom.style.display = (filterType === 'custom') ? 'block' : 'none';
    fieldDateTo.style.display = (filterType === 'custom') ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    togglePeriodFields();
});
</script>
@endpush
@endsection
