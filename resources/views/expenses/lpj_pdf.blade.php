<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>REKAP TALANGAN PRIBADI DANA BOSP</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #666; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signature-table { width: 100%; margin-top: 40px; }
        .signature-table td { width: 50%; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP TALANGAN PRIBADI DANA BOSP</h2>
        <p><strong>{{ $school->name }}</strong></p>
        <p>NPSN: {{ $school->npsn ?: '-' }} | Alamat: {{ $school->address ?: '-' }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 18%;">Pengaju</th>
                <th style="width: 15%;">Kategori BOSP</th>
                <th>Uraian Keperluan</th>
                <th style="width: 15%;">Nominal</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($expenses as $idx => $exp)
                @php $grandTotal += $exp->nominal; @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $exp->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $exp->user->name }}</td>
                    <td>{{ $exp->category->nama_kategori }}</td>
                    <td>{{ $exp->uraian }} ({{ $exp->toko_vendor ?: 'Vendor' }})</td>
                    <td class="text-right">Rp {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $exp->status }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL KESELURUHAN PENGELUARAN:</th>
                <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <p>Mengetahui,<br><strong>Kepala Sekolah</strong></p>
                <br><br><br>
                <p><u><strong>{{ $school->kepala_sekolah_nama ?: '____________________' }}</strong></u><br>NIP. {{ $school->kepala_sekolah_nip ?: '-------------------' }}</p>
            </td>
            <td>
                <p>Menyetujui,<br><strong>Bendahara Sekolah</strong></p>
                <br><br><br>
                <p><u><strong>{{ $school->bendahara_nama ?: '____________________' }}</strong></u><br>NIP. {{ $school->bendahara_nip ?: '-------------------' }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
