<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Bulanan - {{ $rombel->nama_rombel }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 20px 25px 20px 25px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #1e293b;
            line-height: 1.2;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .school-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            color: #16a34a;
        }
        .doc-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 9.5px;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .rekap-table th, .rekap-table td {
            border: 1px solid #94a3b8;
            padding: 4px 1px;
            text-align: center;
            font-size: 8px;
        }
        .rekap-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            font-size: 7.5px;
        }
        .rekap-table td.left-align {
            text-align: left;
            padding-left: 5px;
            font-weight: bold;
            font-size: 8.5px;
        }
        .status-h { color: #16a34a; font-weight: bold; }
        .status-s { color: #d97706; font-weight: bold; }
        .status-i { color: #0284c7; font-weight: bold; }
        .status-a { color: #dc2626; font-weight: bold; }
        .status-t { color: #64748b; font-weight: bold; }
        .status-dash { color: #cbd5e1; }
        .summary-col {
            font-weight: bold;
            background-color: #f8fafc;
        }
        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9.5px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="school-title">{{ $school->name }}</div>
                <div class="doc-title">REKAPITULASI PRESENSI BULANAN SISWA</div>
                <div style="font-size: 8.5px; color: #64748b;">
                    {{ $school->jenjang ?? 'TK/PAUD' }} | NPSN: {{ $school->npsn ?? '-' }} &bull; {{ $school->address ?? 'Alamat Sekolah' }}
                </div>
            </td>
            <td style="width: 30%; text-align: right; font-size: 9px; color: #64748b;">
                <strong>SekolahKu Academic</strong><br>
                Tanggal Cetak: {{ $printedAt }}
            </td>
        </tr>
    </table>

    <!-- Metadata Section -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>Kelas / Rombel</strong></td>
            <td style="width: 35%;">: {{ $rombel->nama_rombel }} (Tingkat {{ $rombel->tingkat }})</td>
            <td style="width: 15%;"><strong>Periode Bulan</strong></td>
            <td style="width: 35%;">: {{ $periodeLabel }}</td>
        </tr>
        <tr>
            <td><strong>Wali Kelas</strong></td>
            <td>: {{ $rombel->waliKelas?->nama ?? 'Belum Ditentukan' }}</td>
            <td><strong>Jumlah Siswa</strong></td>
            <td>: {{ count($recap) }} Siswa</td>
        </tr>
    </table>

    <!-- Main Attendance Matrix Table -->
    <table class="rekap-table">
        <thead>
            <tr>
                <th style="width: 25px;" rowspan="2">No</th>
                <th style="width: 150px;" rowspan="2">Nama Siswa</th>
                <th style="width: 65px;" rowspan="2">NISN</th>
                <th colspan="{{ $daysInMonth }}">Tanggal (1 s/d {{ $daysInMonth }})</th>
                <th colspan="5">Keterangan</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    <th style="width: 15px;">{{ $d }}</th>
                @endfor
                <th class="summary-col" style="color: #16a34a; width: 18px;">H</th>
                <th class="summary-col" style="color: #d97706; width: 18px;">S</th>
                <th class="summary-col" style="color: #0284c7; width: 18px;">I</th>
                <th class="summary-col" style="color: #dc2626; width: 18px;">A</th>
                <th class="summary-col" style="color: #64748b; width: 18px;">T</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recap as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left-align">{{ $row['nama_lengkap'] }}</td>
                    <td style="font-family: monospace;">{{ $row['nisn'] }}</td>
                    
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                            $status = $row['details'][$dateStr] ?? null;
                        @endphp
                        <td>
                            @if($status === 'Hadir')
                                <span class="status-h">H</span>
                            @elseif($status === 'Sakit')
                                <span class="status-s">S</span>
                            @elseif($status === 'Izin')
                                <span class="status-i">I</span>
                            @elseif($status === 'Alpa')
                                <span class="status-a">A</span>
                            @elseif($status === 'Terlambat')
                                <span class="status-t">T</span>
                            @else
                                <span class="status-dash">-</span>
                            @endif
                        </td>
                    @endfor

                    <td class="summary-col status-h">{{ $row['hadir'] }}</td>
                    <td class="summary-col status-s">{{ $row['sakit'] }}</td>
                    <td class="summary-col status-i">{{ $row['izin'] }}</td>
                    <td class="summary-col status-a">{{ $row['alpa'] }}</td>
                    <td class="summary-col status-t">{{ $row['terlambat'] }}</td>
                </tr>
            @endforeach

            @if(empty($recap))
                <tr>
                    <td colspan="{{ 8 + $daysInMonth }}" style="padding: 15px; color: #64748b;">
                        Tidak ada data presensi yang tercatat untuk periode ini.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Signature Section -->
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
                {{ $school->address ? \Illuminate\Support\Str::afterLast($school->address, ',') : 'Kota' }}, {{ $printedAt }}<br>
                <strong>Wali Kelas {{ $rombel->nama_rombel }}</strong>
                <br><br><br><br><br>
                <u><strong>{{ $rombel->waliKelas?->nama ?? (auth()->user()->name ?? '............................................') }}</strong></u><br>
                NIP. {{ $rombel->waliKelas?->nip ?? '............................................' }}
            </td>
        </tr>
    </table>

</body>
</html>
