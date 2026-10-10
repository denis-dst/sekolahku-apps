@extends('layouts.app')

@section('title', 'Buku Kas Umum (BKU) - Pembukuan Revitalisasi')
@section('page_title', 'Buku Kas Umum (BKU)')

@section('content')
    <!-- Top Action & Navigation Header -->
    <div class="card-custom p-3 p-sm-4 mb-4 bg-white shadow-xs">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <a href="{{ route('revitalisasi.index', ['proyek_id' => $proyek->id]) }}" class="btn btn-sm btn-light border py-0.5 px-2">
                        <i class="bi bi-arrow-left me-1"></i> Dashboard Revitalisasi
                    </a>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 fw-bold">
                        Tahun Anggaran {{ $proyek->tahun_anggaran }}
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5">
                        <i class="bi bi-check-circle me-1"></i> Format Resmi 2 Sisi
                    </span>
                </div>
                <h4 class="fw-bold text-dark m-0">Buku Kas Umum (BKU)</h4>
                <div class="text-muted small mt-0.5">
                    {{ $proyek->nama_kegiatan }} • {{ $proyek->school->name ?? 'Sekolah' }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('revitalisasi.bpkt', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold">
                    <i class="bi bi-wallet2 me-1"></i> Buka BPKT
                </a>
                <a href="{{ route('revitalisasi.bb', $proyek) }}" class="btn btn-outline-primary btn-sm rounded-3 fw-semibold">
                    <i class="bi bi-bank me-1"></i> Buka BB
                </a>
                <a href="{{ route('revitalisasi.export-excel', $proyek) }}" class="btn btn-success btn-sm rounded-3 fw-bold px-3">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
                </a>
                <a href="{{ route('revitalisasi.export-pdf', [$proyek, 'type' => 'bku']) }}" class="btn btn-danger btn-sm rounded-3 fw-bold px-3">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Official Ledger Table Container -->
    <div class="card-custom p-4 bg-white shadow-xs mb-4">
        <!-- School & Document Header for Display/Print -->
        <div class="text-center mb-4 pb-3 border-bottom">
            <h5 class="fw-bold text-uppercase m-0" style="letter-spacing: 0.5px; color: #0f172a;">BUKU KAS UMUM (BKU)</h5>
            <h6 class="fw-semibold text-muted text-uppercase mt-1" style="font-size: 0.9rem;">{{ $proyek->nama_kegiatan }}</h6>
            <div class="small text-secondary">
                TAHUN ANGGARAN {{ $proyek->tahun_anggaran }} • REK. {{ $proyek->nama_bank ?: 'BANK' }} {{ $proyek->nomor_rekening }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" style="font-size: 0.84rem; border-color: #cbd5e1;">
                <thead>
                    <tr class="bg-light text-center fw-bold">
                        <th colspan="5" class="py-2.5 text-uppercase bg-primary-subtle text-primary border-bottom border-2 border-primary">
                            Penerimaan
                        </th>
                        <th colspan="6" class="py-2.5 text-uppercase bg-danger-subtle text-danger border-bottom border-2 border-danger">
                            Pengeluaran
                        </th>
                    </tr>
                    <tr class="bg-light text-center text-secondary small fw-bold">
                        <!-- Penerimaan -->
                        <th style="width: 40px;">No.</th>
                        <th style="width: 85px;">Tanggal</th>
                        <th>Uraian</th>
                        <th style="width: 60px;">No. Bukti</th>
                        <th style="width: 120px;">Jumlah (Rp)</th>
                        <!-- Pengeluaran -->
                        <th style="width: 40px;">No.</th>
                        <th style="width: 85px;">Tanggal</th>
                        <th>Uraian</th>
                        <th style="width: 60px;">No. Bukti</th>
                        <th style="width: 95px;">Jenis Biaya</th>
                        <th style="width: 120px;">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $maxRows = max(count($receipts), count($expenses));
                    @endphp

                    @for($i = 0; $i < $maxRows; $i++)
                        @php
                            $r = $receipts[$i] ?? null;
                            $e = $expenses[$i] ?? null;
                        @endphp
                        <tr>
                            <!-- Sisi Penerimaan -->
                            <td class="text-center text-muted">{{ $r ? $r->no : '' }}</td>
                            <td class="text-center text-nowrap">{{ $r && $r->tanggal ? $r->tanggal->format('d/m/Y') : '' }}</td>
                            <td>{{ $r ? $r->uraian : '' }}</td>
                            <td class="text-center fw-bold text-primary">{{ $r ? $r->nomor_bukti : '' }}</td>
                            <td class="text-end fw-bold text-success">{{ $r ? number_format($r->jumlah, 0, ',', '.') : '' }}</td>

                            <!-- Sisi Pengeluaran -->
                            <td class="text-center text-muted">{{ $e ? $e->no : '' }}</td>
                            <td class="text-center text-nowrap">{{ $e && $e->tanggal ? $e->tanggal->format('d/m/Y') : '' }}</td>
                            <td class="{{ $e && !$e->is_parent && str_starts_with($e->uraian, '-') ? 'ps-4 text-secondary' : 'fw-semibold text-dark' }}">
                                {{ $e ? $e->uraian : '' }}
                            </td>
                            <td class="text-center fw-bold text-primary">{{ $e ? $e->nomor_bukti : '' }}</td>
                            <td class="text-center">
                                @if($e && $e->jenis_biaya)
                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded-2">{{ $e->jenis_biaya }}</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $e && $e->jumlah !== null ? 'text-danger' : '' }}">
                                {{ $e && $e->jumlah !== null ? number_format($e->jumlah, 0, ',', '.') : '' }}
                            </td>
                        </tr>
                    @endfor

                    @if($maxRows === 0)
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada penerimaan</td>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pengeluaran</td>
                        </tr>
                    @endif

                    <!-- Baris TOTAL -->
                    <tr class="table-light fw-bold">
                        <td colspan="4" class="text-center py-2.5 text-uppercase">TOTAL</td>
                        <td class="text-end py-2.5 text-success">Rp {{ number_format($summary->total_terima_dana, 0, ',', '.') }}</td>
                        <td colspan="5" class="text-center py-2.5 text-uppercase">TOTAL</td>
                        <td class="text-end py-2.5 text-danger">Rp {{ number_format($summary->total_pengeluaran_bku, 0, ',', '.') }}</td>
                    </tr>

                    <!-- Baris SALDO KAS UMUM -->
                    <tr class="fw-bold">
                        <td colspan="5" class="border-0 bg-white"></td>
                        <td colspan="5" class="text-uppercase bg-light">SALDO KAS UMUM</td>
                        <td class="text-end bg-light text-primary">Rp {{ number_format($summary->saldo_bku, 0, ',', '.') }}</td>
                    </tr>

                    <!-- Baris SALDO KAS TUNAI -->
                    <tr class="fw-bold">
                        <td colspan="5" class="border-0 bg-white"></td>
                        <td colspan="5" class="text-uppercase bg-light">SALDO KAS TUNAI (BPKT)</td>
                        <td class="text-end bg-light text-warning-emphasis">Rp {{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}</td>
                    </tr>

                    <!-- Baris SALDO BANK -->
                    <tr class="fw-bold">
                        <td colspan="5" class="border-0 bg-white"></td>
                        <td colspan="5" class="text-uppercase bg-light">SALDO BANK (BB)</td>
                        <td class="text-end bg-light text-info-emphasis">Rp {{ number_format($summary->saldo_bank, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Penutupan Keadaan Buku -->
        <div class="mt-4 p-3 bg-light rounded-3 border">
            <p class="mb-2 fw-semibold text-dark">
                Pada hari ini : <strong>{{ $proyek->tanggal_tutup_buku ? $proyek->tanggal_tutup_buku->translatedFormat('l') : '.........' }}</strong> 
                Tanggal <strong>{{ $proyek->tanggal_tutup_buku ? $proyek->tanggal_tutup_buku->translatedFormat('d F Y') : '....................' }}</strong> 
                Buku Kas Umum ditutup dengan keadaan/posisi Buku sebagai berikut :
            </p>
            <div class="row g-2 small">
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-between p-2 bg-white rounded border">
                        <span class="fw-bold text-dark">Saldo Buku Kas Umum:</span>
                        <strong class="text-primary">Rp {{ number_format($summary->saldo_bku, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-between p-2 bg-white rounded border">
                        <span>1. Saldo Bank (BB):</span>
                        <strong class="text-info-emphasis">Rp {{ number_format($summary->saldo_bank, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 bg-white rounded border mt-1">
                        <span>2. Saldo Kas Tunai (BPKT):</span>
                        <strong class="text-warning-emphasis">Rp {{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
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
