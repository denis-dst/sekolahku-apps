<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevitalisasiProyek extends Model
{
    use HasFactory;

    protected $table = 'revitalisasi_proyeks';

    protected $fillable = [
        'school_id',
        'nama_kegiatan',
        'tahun_anggaran',
        'nomor_pks',
        'nomor_sk',
        'nama_bank',
        'nomor_rekening',
        'atas_nama_rekening',
        'pagu_anggaran',
        'lokasi_surat',
        'tanggal_tutup_buku',
        'penanggung_jawab_nama',
        'penanggung_jawab_nip',
        'ketua_p2sp_nama',
        'ketua_p2sp_nip',
        'bendahara_nama',
        'bendahara_nip',
        'pengawas_nama',
        'pengawas_nip',
        'status',
    ];

    protected $casts = [
        'tahun_anggaran' => 'integer',
        'pagu_anggaran' => 'decimal:2',
        'tanggal_tutup_buku' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function transaksis()
    {
        return $this->hasMany(RevitalisasiTransaksi::class, 'revitalisasi_proyek_id')
            ->orderBy('tanggal', 'asc')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Hitung Rekonsiliasi & Saldo Buku
     */
    public function getSummaryAttribute()
    {
        $transaksis = $this->transaksis()->with('items')->get();

        $totalTerimaDana = 0;
        $totalPengeluaranBank = 0;
        $totalTarikBank = 0;
        $totalBelanjaTunai = 0;
        $totalBelanjaTransfer = 0;
        $totalPengeluaranBku = 0;

        $rincianBiaya = [
            'Bahan' => 0,
            'Alat' => 0,
            'Upah' => 0,
            'Manajemen' => 0,
            'Lainnya' => 0,
        ];

        foreach ($transaksis as $trx) {
            $nom = (float) $trx->nominal;

            if ($trx->jenis_transaksi === 'terima_dana') {
                $totalTerimaDana += $nom;
            } elseif ($trx->jenis_transaksi === 'tarik_bank') {
                $totalTarikBank += $nom;
                $totalPengeluaranBank += $nom;
            } elseif ($trx->jenis_transaksi === 'belanja_transfer') {
                $totalBelanjaTransfer += $nom;
                $totalPengeluaranBank += $nom;
                $totalPengeluaranBku += $nom;

                $this->accumulateBiaya($trx, $rincianBiaya);
            } elseif ($trx->jenis_transaksi === 'belanja_tunai') {
                $totalBelanjaTunai += $nom;
                $totalPengeluaranBku += $nom;

                $this->accumulateBiaya($trx, $rincianBiaya);
            }
        }

        // Saldo Buku Bank = Penerimaan Bank (Terima Dana) - Pengeluaran Bank (Tarik Tunai + Belanja Transfer)
        $saldoBank = $totalTerimaDana - $totalPengeluaranBank;

        // Saldo Buku Kas Tunai = Penerimaan Kas Tunai (Tarik Bank) - Pengeluaran Kas Tunai (Belanja Tunai)
        $saldoKasTunai = $totalTarikBank - $totalBelanjaTunai;

        // Saldo BKU = Penerimaan BKU (Terima Dana) - Pengeluaran BKU (Belanja Tunai + Belanja Transfer)
        $saldoBku = $totalTerimaDana - $totalPengeluaranBku;

        // Cek Keseimbangan (Balance Check)
        $isBalanced = round($saldoBku, 2) === round($saldoBank + $saldoKasTunai, 2);

        return (object) [
            'total_terima_dana' => $totalTerimaDana,
            'total_pengeluaran_bank' => $totalPengeluaranBank,
            'total_tarik_bank' => $totalTarikBank,
            'total_belanja_tunai' => $totalBelanjaTunai,
            'total_belanja_transfer' => $totalBelanjaTransfer,
            'total_pengeluaran_bku' => $totalPengeluaranBku,
            'saldo_bank' => $saldoBank,
            'saldo_kas_tunai' => $saldoKasTunai,
            'saldo_bku' => $saldoBku,
            'is_balanced' => $isBalanced,
            'selisih' => $saldoBku - ($saldoBank + $saldoKasTunai),
            'rincian_biaya' => $rincianBiaya,
            'total_transaksi' => $transaksis->count(),
        ];
    }

    private function accumulateBiaya($trx, array &$rincianBiaya): void
    {
        if ($trx->items->isNotEmpty()) {
            foreach ($trx->items as $item) {
                $cat = $item->jenis_biaya ?: ($trx->jenis_biaya ?: 'Lainnya');
                if (!isset($rincianBiaya[$cat])) {
                    $rincianBiaya[$cat] = 0;
                }
                $rincianBiaya[$cat] += (float) $item->subtotal;
            }
        } else {
            $cat = $trx->jenis_biaya ?: 'Lainnya';
            if (!isset($rincianBiaya[$cat])) {
                $rincianBiaya[$cat] = 0;
            }
            $rincianBiaya[$cat] += (float) $trx->nominal;
        }
    }
}
