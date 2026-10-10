@extends('layouts.app')

@section('title', 'Buku Bank (BB) - Pembukuan Revitalisasi')
@section('page_title', 'Buku Bank (BB)')

@section('content')
    <!-- Top Action & Navigation Header -->
    <div class="card-custom p-3 p-sm-4 mb-4 bg-white shadow-xs">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <a href="{{ route('revitalisasi.index', ['proyek_id' => $proyek->id]) }}" class="btn btn-sm btn-light border py-0.5 px-2">
                        <i class="bi bi-arrow-left me-1"></i> Dashboard Revitalisasi
                    </a>
                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-0.5 fw-bold">
                        Rekening Bank Resmi
                    </span>
                    <span class="badge bg-light text-dark border px-2 py-0.5">
                        Tahun Anggaran {{ $proyek->tahun_anggaran }}
                    </span>
                </div>
                <h4 class="fw-bold text-dark m-0">Buku Bank (BB)</h4>
                <div class="text-muted small mt-0.5">
                    Mencatat seluruh mutasi rekening penampung bantuan revitalisasi (Penerimaan Termin, Penarikan Tunai & Pembayaran Transfer)
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('revitalisasi.bku', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold">
                    <i class="bi bi-journal-text me-1"></i> Buka BKU
                </a>
                <a href="{{ route('revitalisasi.bpkt', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold">
                    <i class="bi bi-wallet2 me-1"></i> Buka BPKT
                </a>
                <a href="{{ route('revitalisasi.export-excel', $proyek) }}" class="btn btn-success btn-sm rounded-3 fw-bold px-3">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                </a>
                <a href="{{ route('revitalisasi.export-pdf', [$proyek, 'type' => 'bb']) }}" class="btn btn-danger btn-sm rounded-3 fw-bold px-3">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Official Ledger Table Container -->
    <div class="card-custom p-4 bg-white shadow-xs mb-4">
        <!-- School & Document Header for Display/Print -->
        <div class="text-center mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-uppercase m-0" style="letter-spacing: 0.5px; color: #0f172a;">BUKU BANK (BB)</h5>
            <h6 class="fw-semibold text-muted text-uppercase mt-1" style="font-size: 0.9rem;">{{ $proyek->nama_kegiatan }}</h6>
            <div class="small text-secondary">
                {{ $proyek->nama_bank ?: 'BANK' }} - NO. REK: <strong>{{ $proyek->nomor_rekening ?: '-' }}</strong> A.N <strong>{{ $proyek->atas_nama_rekening ?: ($proyek->school->name ?? '-') }}</strong>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="font-size: 0.84rem; border-color: #cbd5e1;">
                <thead class="bg-light text-center text-secondary fw-bold">
                    <tr>
                        <th style="width: 45px;">No.</th>
                        <th style="width: 95px;">Tanggal</th>
                        <th>Uraian Transaksi Bank</th>
                        <th style="width: 75px;">No. Bukti</th>
                        <th style="width: 140px;" class="text-success">Penerimaan (Rp)</th>
                        <th style="width: 140px;" class="text-danger">Pengeluaran (Rp)</th>
                        <th style="width: 145px;" class="text-primary">Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="text-center text-muted">{{ $row->no }}</td>
                            <td class="text-center text-nowrap">{{ $row->tanggal ? $row->tanggal->format('d/m/Y') : '' }}</td>
                            <td class="fw-semibold text-dark">{{ $row->uraian }}</td>
                            <td class="text-center fw-bold text-primary">{{ $row->nomor_bukti }}</td>
                            <td class="text-end fw-bold {{ $row->penerimaan ? 'text-success' : '' }}">
                                {{ $row->penerimaan ? number_format($row->penerimaan, 0, ',', '.') : '' }}
                            </td>
                            <td class="text-end fw-bold {{ $row->pengeluaran ? 'text-danger' : '' }}">
                                {{ $row->pengeluaran ? number_format($row->pengeluaran, 0, ',', '.') : '' }}
                            </td>
                            <td class="text-end fw-bold text-dark bg-light-subtle">
                                {{ number_format($row->saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada mutasi transaksi bank</td>
                        </tr>
                    @endforelse

                    <!-- Baris TOTAL -->
                    <tr class="table-light fw-bold">
                        <td colspan="4" class="text-center py-2.5 text-uppercase">TOTAL</td>
                        <td class="text-end py-2.5 text-success">Rp {{ number_format($summary->total_terima_dana, 0, ',', '.') }}</td>
                        <td class="text-end py-2.5 text-danger">Rp {{ number_format($summary->total_pengeluaran_bank, 0, ',', '.') }}</td>
                        <td class="text-end py-2.5 text-primary">Rp {{ number_format($summary->saldo_bank, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Empat (4) Blok Tanda Tangan Resmi -->
        <div class="mt-5 pt-3">
            <div class="text-center text-muted small mb-3">
                {{ $proyek->lokasi_surat ?: 'Jakarta' }}, {{ $proyek->tanggal_tutup_buku ? $proyek->tanggal_tutup_buku->translatedFormat('d F Y') : date('d F Y') }}
            </div>
            <div class="text-center fw-bold text-uppercase text-dark mb-4" style="font-size: 0.9rem;">
                Mengetahui :
            </div>
            <div class="row text-center g-4">
                <div class="col-6 col-md-3">
                    <div class="fw-bold text-secondary text-uppercase small">Penanggung Jawab / KS</div>
                    <div style="height: 65px;"></div>
                    <div class="fw-bold text-dark text-decoration-underline">{{ $proyek->penanggung_jawab_nama ?: '( ................................ )' }}</div>
                    <div class="text-muted small">{{ $proyek->penanggung_jawab_nip ? 'NIP. ' . $proyek->penanggung_jawab_nip : 'Kepala Sekolah' }}</div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="fw-bold text-secondary text-uppercase small">Ketua Tim P2SP</div>
                    <div style="height: 65px;"></div>
                    <div class="fw-bold text-dark text-decoration-underline">{{ $proyek->ketua_p2sp_nama ?: '( ................................ )' }}</div>
                    <div class="text-muted small">{{ $proyek->ketua_p2sp_nip && $proyek->ketua_p2sp_nip !== '-' ? 'NIP. ' . $proyek->ketua_p2sp_nip : 'Ketua Panitia' }}</div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="fw-bold text-secondary text-uppercase small">Bendahara Kegiatan</div>
                    <div style="height: 65px;"></div>
                    <div class="fw-bold text-dark text-decoration-underline">{{ $proyek->bendahara_nama ?: '( ................................ )' }}</div>
                    <div class="text-muted small">{{ $proyek->bendahara_nip && $proyek->bendahara_nip !== '-' ? 'NIP. ' . $proyek->bendahara_nip : 'Bendahara' }}</div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="fw-bold text-secondary text-uppercase small">Pengawas / Tim Teknis</div>
                    <div style="height: 65px;"></div>
                    <div class="fw-bold text-dark text-decoration-underline">{{ $proyek->pengawas_nama ?: '( ................................ )' }}</div>
                    <div class="text-muted small">{{ $proyek->pengawas_nip && $proyek->pengawas_nip !== '-' ? 'NIP. ' . $proyek->pengawas_nip : 'Pengawas' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
