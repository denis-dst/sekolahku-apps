<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-RAPOR DIGITAL - {{ $siswa->nama_lengkap }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .section-title { font-size: 13px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 15px; }
        .table th, .table td { border: 1px solid #666; padding: 6px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .signature-table { width: 100%; margin-top: 30px; }
        .signature-table td { width: 50%; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN CAPAIAN PEMBELAJARAN (E-RAPOR)</h2>
        <p><strong>{{ $school->name }}</strong></p>
        <p>NPSN: {{ $school->npsn ?: '-' }} | {{ $school->address ?: 'Alamat Sekolah' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%;"><strong>Nama Siswa</strong></td>
            <td style="width: 35%;">: {{ $siswa->nama_lengkap }}</td>
            <td style="width: 15%;"><strong>Kelas / Rombel</strong></td>
            <td style="width: 35%;">: {{ $siswa->rombel->nama_rombel ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>NISN / NIK</strong></td>
            <td>: {{ $siswa->nisn ?: '-' }} / {{ $siswa->nik ?: '-' }}</td>
            <td><strong>Tahun Ajaran</strong></td>
            <td>: {{ $siswa->rombel->tahunAjaran->name ?? '2025/2026' }}</td>
        </tr>
    </table>

    <div class="section-title">A. CAPAIAN PEMBELAJARAN & ASSESSMEN</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Elemen Pembelajaran</th>
                <th style="width: 15%;">Jenis / Nilai</th>
                <th>Deskripsi Capaian Perkembangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa->assessments as $idx => $ass)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $ass->mata_pelajaran }}</strong></td>
                    <td style="text-align: center;">{{ $ass->jenis_penilaian }} {{ $ass->nilai_angka ? '('.$ass->nilai_angka.')' : '' }}</td>
                    <td>{{ $ass->capaian_narasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #888;">Belum ada data penilaian tersimpan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">B. REKAPITULASI PRESENSI</div>
    @php
        $hadir = $siswa->presensis->where('status', 'Hadir')->count();
        $izin = $siswa->presensis->where('status', 'Izin')->count();
        $sakit = $siswa->presensis->where('status', 'Sakit')->count();
        $alpa = $siswa->presensis->where('status', 'Alpa')->count();
    @endphp
    <table class="table" style="width: 60%;">
        <tr>
            <th>Status Kehadiran</th>
            <th style="text-align: center;">Jumlah Hari</th>
        </tr>
        <tr><td>Hadir</td><td style="text-align: center;">{{ $hadir }} Hari</td></tr>
        <tr><td>Izin</td><td style="text-align: center;">{{ $izin }} Hari</td></tr>
        <tr><td>Sakit</td><td style="text-align: center;">{{ $sakit }} Hari</td></tr>
        <tr><td>Alpa / Tanpa Keterangan</td><td style="text-align: center;">{{ $alpa }} Hari</td></tr>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <p>Mengetahui,<br><strong>Orang Tua / Wali Siswa</strong></p>
                <br><br><br>
                <p><u>________________________</u></p>
            </td>
            <td>
                <p>Mengetahui,<br><strong>Wali Kelas</strong></p>
                <br><br><br>
                <p><u><strong>{{ $siswa->rombel->waliKelas->nama_lengkap ?? '____________________' }}</strong></u><br>NIP. {{ $siswa->rombel->waliKelas->nip ?? '-------------------' }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
