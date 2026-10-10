<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Pembantu Kas Tunai (BPKT) - {{ $proyek->nama_kegiatan }}</title>
    <style>
        @page {
            margin: 20px 25px;
            size: a4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        .header-title {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .header-sub {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 12px;
        }
        table.ledger {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.ledger th, table.ledger td {
            border: 1px solid #64748b;
            padding: 5px 8px;
            vertical-align: middle;
        }
        table.ledger th {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 8.5pt;
        }
        .bg-light { background-color: #f8fafc; }
        .sig-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }
        .sig-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="text-center">
        <div class="header-title">BUKU PEMBANTU KAS TUNAI (BPKT)</div>
        <div class="header-sub">{{ strtoupper($proyek->nama_kegiatan) }}</div>
        <div style="font-size: 8.5pt; margin-bottom: 10px;">
            TAHUN ANGGARAN {{ $proyek->tahun_anggaran }} • {{ $school->name ?? 'SEKOLAH' }}
        </div>
    </div>

    <table class="ledger">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Uraian Transaksi</th>
                <th style="width: 60px;">No. Bukti</th>
                <th style="width: 120px;">Penerimaan (Rp)</th>
                <th style="width: 120px;">Pengeluaran (Rp)</th>
                <th style="width: 130px;">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td class="text-center">{{ $row->no }}</td>
                    <td class="text-center">{{ $row->tanggal ? $row->tanggal->format('d/m/Y') : '' }}</td>
                    <td style="{{ $row->is_subitem ? 'padding-left: 20px;' : '' }}">
                        {{ $row->uraian }}
                    </td>
                    <td class="text-center">{{ $row->nomor_bukti }}</td>
                    <td class="text-end">{{ $row->penerimaan ? number_format($row->penerimaan, 0, ',', '.') : '' }}</td>
                    <td class="text-end">{{ $row->pengeluaran ? number_format($row->pengeluaran, 0, ',', '.') : '' }}</td>
                    <td class="text-end fw-bold">{{ number_format($row->saldo, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- Total -->
            <tr class="fw-bold bg-light">
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-end">{{ number_format($summary->total_tarik_bank, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($summary->total_belanja_tunai, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Signatures -->
    <div style="margin-top: 30px;">
        <div class="text-center" style="font-size: 8.5pt; margin-bottom: 5px;">
            {{ $proyek->lokasi_surat ?: 'Jakarta' }}, {{ $proyek->tanggal_tutup_buku ? $proyek->tanggal_tutup_buku->translatedFormat('d F Y') : date('d F Y') }}
        </div>
        <div class="text-center fw-bold text-uppercase" style="font-size: 8.5pt; margin-bottom: 15px;">Mengetahui :</div>
        <table class="sig-table">
            <tr>
                <td style="width: 25%;">
                    <div class="fw-bold">Penanggung Jawab / KS</div>
                    <div style="height: 50px;"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ $proyek->penanggung_jawab_nama ?: '( ................................ )' }}</div>
                    <div style="font-size: 7.5pt;">{{ $proyek->penanggung_jawab_nip ? 'NIP. ' . $proyek->penanggung_jawab_nip : '' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="fw-bold">Ketua Tim P2SP</div>
                    <div style="height: 50px;"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ $proyek->ketua_p2sp_nama ?: '( ................................ )' }}</div>
                    <div style="font-size: 7.5pt;">{{ $proyek->ketua_p2sp_nip && $proyek->ketua_p2sp_nip !== '-' ? 'NIP. ' . $proyek->ketua_p2sp_nip : '' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="fw-bold">Bendahara Kegiatan</div>
                    <div style="height: 50px;"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ $proyek->bendahara_nama ?: '( ................................ )' }}</div>
                    <div style="font-size: 7.5pt;">{{ $proyek->bendahara_nip && $proyek->bendahara_nip !== '-' ? 'NIP. ' . $proyek->bendahara_nip : '' }}</div>
                </td>
                <td style="width: 25%;">
                    <div class="fw-bold">Pengawas / Tim Teknis</div>
                    <div style="height: 50px;"></div>
                    <div class="fw-bold" style="text-decoration: underline;">{{ $proyek->pengawas_nama ?: '( ................................ )' }}</div>
                    <div style="font-size: 7.5pt;">{{ $proyek->pengawas_nip && $proyek->pengawas_nip !== '-' ? 'NIP. ' . $proyek->pengawas_nip : '' }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
