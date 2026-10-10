<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Kas Umum (BKU) - {{ $proyek->nama_kegiatan }}</title>
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
            padding: 4px 6px;
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
        <div class="header-title">BUKU KAS UMUM (BKU)</div>
        <div class="header-sub">{{ strtoupper($proyek->nama_kegiatan) }}</div>
        <div style="font-size: 8.5pt; margin-bottom: 10px;">
            TAHUN ANGGARAN {{ $proyek->tahun_anggaran }} • {{ $school->name ?? 'SEKOLAH' }}
        </div>
    </div>

    <table class="ledger">
        <thead>
            <tr>
                <th colspan="5" class="text-center bg-light">PENERIMAAN</th>
                <th colspan="6" class="text-center bg-light">PENGELUARAN</th>
            </tr>
            <tr>
                <th style="width: 25px;">No.</th>
                <th style="width: 65px;">Tanggal</th>
                <th>Uraian</th>
                <th style="width: 45px;">No. Bukti</th>
                <th style="width: 95px;">Jumlah (Rp)</th>

                <th style="width: 25px;">No.</th>
                <th style="width: 65px;">Tanggal</th>
                <th>Uraian</th>
                <th style="width: 45px;">No. Bukti</th>
                <th style="width: 75px;">Jenis Biaya</th>
                <th style="width: 95px;">Jumlah (Rp)</th>
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
                    <!-- Penerimaan -->
                    <td class="text-center">{{ $r ? $r->no : '' }}</td>
                    <td class="text-center">{{ $r && $r->tanggal ? $r->tanggal->format('d/m/Y') : '' }}</td>
                    <td>{{ $r ? $r->uraian : '' }}</td>
                    <td class="text-center">{{ $r ? $r->nomor_bukti : '' }}</td>
                    <td class="text-end">{{ $r ? number_format($r->jumlah, 0, ',', '.') : '' }}</td>

                    <!-- Pengeluaran -->
                    <td class="text-center">{{ $e ? $e->no : '' }}</td>
                    <td class="text-center">{{ $e && $e->tanggal ? $e->tanggal->format('d/m/Y') : '' }}</td>
                    <td style="{{ $e && !$e->is_parent && str_starts_with($e->uraian, '-') ? 'padding-left: 15px;' : '' }}">
                        {{ $e ? $e->uraian : '' }}
                    </td>
                    <td class="text-center">{{ $e ? $e->nomor_bukti : '' }}</td>
                    <td class="text-center">{{ $e ? $e->jenis_biaya : '' }}</td>
                    <td class="text-end">{{ $e && $e->jumlah !== null ? number_format($e->jumlah, 0, ',', '.') : '' }}</td>
                </tr>
            @endfor

            <!-- Total -->
            <tr class="fw-bold bg-light">
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-end">{{ number_format($summary->total_terima_dana, 0, ',', '.') }}</td>
                <td colspan="5" class="text-center">TOTAL</td>
                <td class="text-end">{{ number_format($summary->total_pengeluaran_bku, 0, ',', '.') }}</td>
            </tr>

            <!-- Saldo Kas Umum -->
            <tr class="fw-bold">
                <td colspan="5" style="border:none;"></td>
                <td colspan="5">SALDO KAS UMUM</td>
                <td class="text-end">{{ number_format($summary->saldo_bku, 0, ',', '.') }}</td>
            </tr>

            <!-- Saldo Kas Tunai -->
            <tr class="fw-bold">
                <td colspan="5" style="border:none;"></td>
                <td colspan="5">SALDO KAS TUNAI</td>
                <td class="text-end">{{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}</td>
            </tr>

            <!-- Saldo Bank -->
            <tr class="fw-bold">
                <td colspan="5" style="border:none;"></td>
                <td colspan="5">SALDO BANK</td>
                <td class="text-end">{{ number_format($summary->saldo_bank, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 15px; font-size: 8.5pt;">
        Pada hari ini Tanggal {{ $proyek->tanggal_tutup_buku ? $proyek->tanggal_tutup_buku->translatedFormat('d F Y') : date('d F Y') }} Buku Kas Umum ditutup dengan keadaan/posisi Buku sebagai berikut :<br>
        Saldo Buku Kas Umum: <strong>Rp {{ number_format($summary->saldo_bku, 0, ',', '.') }}</strong>, terdiri dari: 
        1. Saldo Bank : <strong>Rp {{ number_format($summary->saldo_bank, 0, ',', '.') }}</strong>, 
        2. Saldo Kas Tunai : <strong>Rp {{ number_format($summary->saldo_kas_tunai, 0, ',', '.') }}</strong>.
    </div>

    <!-- Signatures -->
    <div style="margin-top: 20px;">
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
