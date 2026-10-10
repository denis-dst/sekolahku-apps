<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi No. {{ $transaksi->nomor_bukti }} - {{ $proyek->nama_kegiatan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f1f5f9;
            color: #000000;
            padding: 20px;
        }
        .kuitansi-card {
            background: #ffffff;
            max-width: 850px;
            margin: 0 auto;
            padding: 35px 40px;
            border: 2px solid #000000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header-border {
            border-bottom: 3px double #000000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kuitansi-title {
            letter-spacing: 4px;
            font-weight: 800;
            font-size: 1.4rem;
            text-decoration: underline;
        }
        .table-kuitansi td {
            padding: 5px 8px;
            vertical-align: top;
        }
        .terbilang-box {
            background-color: #f8fafc;
            border: 1px dashed #475569;
            padding: 8px 12px;
            font-style: italic;
            font-weight: bold;
        }
        .nominal-badge {
            font-size: 1.35rem;
            font-weight: 800;
            padding: 6px 16px;
            border: 2px solid #000000;
            display: inline-block;
            background: #f8fafc;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .kuitansi-card {
                box-shadow: none;
                border: 2px solid #000000;
                padding: 25px 30px;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Print Action Bar -->
    <div class="text-center mb-3 no-print">
        <button class="btn btn-primary fw-bold px-4 shadow-sm" onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Cetak Kuitansi Ini
        </button>
        <button class="btn btn-secondary ms-2" onclick="window.close()">Tutup Halaman</button>
    </div>

    <div class="kuitansi-card">
        <!-- Header Kop Lembaga / Sekolah -->
        <div class="header-border text-center">
            <h4 class="fw-bold m-0 text-uppercase">{{ $school->name ?? 'PANITIA PEMBANGUNAN REVITALISASI' }}</h4>
            <div style="font-size: 0.95rem;">{{ $school->address ?? 'Alamat Sekolah / Satuan PAUD' }}</div>
            <div style="font-size: 0.85rem;">Telp: {{ $school->phone ?? '-' }} • Email: {{ $school->email ?? '-' }}</div>
        </div>

        <!-- Akun & Tahun -->
        <div class="d-flex justify-content-between align-items-center mb-3 small fw-bold">
            <div>AKUN / JENIS BIAYA : {{ strtoupper($transaksi->jenis_biaya ?: 'REVITALISASI') }}</div>
            <div>TAHUN ANGGARAN : {{ $proyek->tahun_anggaran }}</div>
        </div>

        <!-- Title Kuitansi -->
        <div class="text-center mb-4">
            <div class="kuitansi-title">K U I T A N S I</div>
            <div class="small fw-bold mt-1">Nomor Bukti : {{ $transaksi->nomor_bukti }}/P2SP/{{ $proyek->tahun_anggaran }}</div>
        </div>

        <!-- Isi Form Kuitansi -->
        <table class="table-kuitansi w-100 mb-4" style="font-size: 1rem;">
            <tr>
                <td style="width: 200px;">Sudah Terima Dari</td>
                <td style="width: 15px;">:</td>
                <td class="fw-bold">Bendahara Panitia Revitalisasi (P2SP) {{ $school->name ?? '' }}</td>
            </tr>
            <tr>
                <td>Uang Sebesar</td>
                <td>:</td>
                <td>
                    <div class="terbilang-box">
                        # {{ $terbilang }} #
                    </div>
                </td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td>
                    <div class="fw-bold">{{ $transaksi->uraian }}</div>
                    @if($transaksi->items->isNotEmpty())
                        <div class="small text-muted mt-1 ps-2 border-start border-2 border-dark">
                            @foreach($transaksi->items as $item)
                                <div>- {{ $item->uraian_item }} = Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if($transaksi->keterangan)
                        <div class="small text-muted mt-1"><em>({{ $transaksi->keterangan }})</em></div>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Terbayar Kepada</td>
                <td>:</td>
                <td class="fw-bold text-uppercase">{{ $transaksi->penerima_vendor ?: 'Pihak Ketiga / Pelaksana' }}</td>
            </tr>
        </table>

        <!-- Box Nominal & Tanggal -->
        <div class="d-flex justify-content-between align-items-center my-4">
            <div>
                <span class="small fw-bold d-block text-muted">Jumlah Pembayaran:</span>
                <div class="nominal-badge">
                    Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                </div>
            </div>
            <div class="text-end">
                <div class="fw-semibold">{{ $proyek->lokasi_surat ?: 'Jakarta' }}, {{ $transaksi->tanggal ? $transaksi->tanggal->translatedFormat('d F Y') : date('d F Y') }}</div>
            </div>
        </div>

        <!-- Tanda Tangan 3 Pihak -->
        <div class="row text-center mt-5 pt-3" style="font-size: 0.95rem;">
            <div class="col-4">
                <div class="fw-bold">Setuju Dibayar,</div>
                <div>Ketua Tim P2SP</div>
                <div style="height: 70px;"></div>
                <div class="fw-bold text-decoration-underline">{{ $proyek->ketua_p2sp_nama ?: '( ................................ )' }}</div>
                <div class="small text-muted">{{ $proyek->ketua_p2sp_nip && $proyek->ketua_p2sp_nip !== '-' ? 'NIP. ' . $proyek->ketua_p2sp_nip : '' }}</div>
            </div>

            <div class="col-4">
                <div class="fw-bold">Lunas Dibayar,</div>
                <div>Bendahara Kegiatan</div>
                <div style="height: 70px;"></div>
                <div class="fw-bold text-decoration-underline">{{ $proyek->bendahara_nama ?: '( ................................ )' }}</div>
                <div class="small text-muted">{{ $proyek->bendahara_nip && $proyek->bendahara_nip !== '-' ? 'NIP. ' . $proyek->bendahara_nip : '' }}</div>
            </div>

            <div class="col-4">
                <div class="fw-bold">Yang Menerima,</div>
                <div>Penerima / Vendor</div>
                <div style="height: 70px;"></div>
                <div class="fw-bold text-decoration-underline">{{ $transaksi->penerima_vendor ?: '( ................................ )' }}</div>
                <div class="small text-muted">Penyedia / Pelaksana</div>
            </div>
        </div>
    </div>
</body>
</html>
