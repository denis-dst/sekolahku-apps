<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Talangan Pribadi Dana BOSP - {{ $school->name }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .school-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #16a34a;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .period-subtitle {
            text-align: center;
            font-size: 11px;
            color: #475569;
            margin-bottom: 20px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 6px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        .content-table td {
            padding: 7px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f8fafc;
            font-size: 11px;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .page-break {
            page-break-before: always;
        }

        .attachment-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #cbd5e1;
            text-transform: uppercase;
            color: #16a34a;
        }

        .receipt-card {
            border: 1px solid #e2e8f0;
            padding: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background-color: #ffffff;
            border-radius: 4px;
        }

        .receipt-image {
            max-width: 100%;
            max-height: 380px;
            display: block;
            margin: 10px auto 0 auto;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="school-title">{{ $school->name }}</div>
                <div>{{ $school->jenjang ?? 'TK/PAUD' }} | NPSN: {{ $school->npsn ?? '-' }}</div>
                <div style="font-size: 10px; color: #64748b;">{{ $school->address ?? 'Alamat Sekolah' }}</div>
            </td>
            <td style="width: 30%; text-align: right; font-size: 10px; color: #64748b;">
                <strong>SekolahKu Finance</strong><br>
                Dicetak: {{ $printedAt ?? date('d F Y H:i') }}
            </td>
        </tr>
    </table>

    <div class="doc-title">Rekapitulasi Talangan Pribadi Dana BOSP</div>
    <div class="period-subtitle">Periode: <strong>{{ $periodLabel ?? 'Semua Periode' }}</strong></div>

    <!-- Main Transaction Table -->
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 65px;">Tanggal</th>
                <th>Pengaju</th>
                <th>Uraian Keperluan</th>
                <th style="width: 90px;">Kategori BOSP</th>
                <th style="width: 80px;">Toko / Vendor</th>
                <th style="width: 85px; text-align: right;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $index => $exp)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $exp->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $exp->user->name ?? '-' }}</td>
                    <td>
                        <strong>{{ $exp->uraian }}</strong>
                        @if($exp->lokasi)<br><small style="color: #64748b;">(Lokasi: {{ $exp->lokasi }})</small>@endif
                    </td>
                    <td>{{ $exp->category->nama_kategori ?? '-' }}</td>
                    <td>{{ $exp->toko_vendor ?? '-' }}</td>
                    <td style="text-align: right; font-weight: bold;">
                        {{ number_format($exp->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #64748b;">
                        Tidak ada pengeluaran talangan yang tercatat untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($expenses->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right; text-transform: uppercase;">Total Keseluruhan Talangan BOSP:</td>
                    <td style="text-align: right; color: #16a34a;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Signature Approval Block -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala {{ $school->name }}</strong>
                <br><br><br><br><br>
                <u><strong>{{ $school->kepala_sekolah_nama ?? '............................................' }}</strong></u><br>
                NIP. {{ $school->kepala_sekolah_nip ?? '............................................' }}
            </td>
            <td>
                {{ $school->address ? \Illuminate\Support\Str::afterLast($school->address, ',') : 'Kota' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Bendahara Sekolah / Pemohon</strong>
                <br><br><br><br><br>
                <u><strong>{{ $school->bendahara_nama ?? (auth()->user()->name ?? '............................................') }}</strong></u><br>
                NIP. {{ $school->bendahara_nip ?? '............................................' }}
            </td>
        </tr>
    </table>

    <!-- Lampiran Bukti Pembayaran / Receipts Pages -->
    @php
        $expensesWithReceipts = $expenses->filter(fn($e) => $e->receipts->count() > 0);
    @endphp

    @if($expensesWithReceipts->count() > 0)
        <div class="page-break"></div>
        <div class="attachment-title">Lampiran Bukti Pembayaran / Nota Transaksi</div>

        @foreach($expensesWithReceipts as $exp)
            @foreach($exp->receipts as $receipt)
                @if($receipt->file_type !== 'pdf' && !str_contains($receipt->file_type, 'pdf'))
                    <div class="receipt-card">
                        <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 5px;">
                            <tr>
                                <td><strong>Uraian:</strong> {{ $exp->uraian }}</td>
                                <td style="text-align: right;"><strong>Tanggal:</strong> {{ $exp->tanggal->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Vendor:</strong> {{ $exp->toko_vendor ?? '-' }} | <strong>Kategori:</strong> {{ $exp->category->nama_kategori ?? '-' }}</td>
                                <td style="text-align: right; font-weight: bold; color: #16a34a;">Rp {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                        
                        @php
                            $path = storage_path('app/public/' . $receipt->file_path);
                        @endphp
                        @if(file_exists($path))
                            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($path)) }}" class="receipt-image">
                        @else
                            <div style="text-align: center; padding: 25px; background: #f8fafc; color: #64748b; font-size: 10px;">
                                [ File nota: {{ $receipt->file_name ?? 'Bukti Nota' }} ]
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        @endforeach
    @endif

</body>
</html>
