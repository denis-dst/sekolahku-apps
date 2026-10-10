<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevitalisasiTransaksi extends Model
{
    use HasFactory;

    protected $table = 'revitalisasi_transaksis';

    protected $fillable = [
        'school_id',
        'revitalisasi_proyek_id',
        'user_id',
        'nomor_bukti',
        'tanggal',
        'jenis_transaksi',
        'uraian',
        'jenis_biaya',
        'penerima_vendor',
        'nominal',
        'keterangan',
        'file_bukti',
        'urutan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function proyek()
    {
        return $this->belongsTo(RevitalisasiProyek::class, 'revitalisasi_proyek_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(RevitalisasiTransaksiItem::class, 'revitalisasi_transaksi_id')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Label representasi jenis transaksi
     */
    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_transaksi) {
            'terima_dana' => 'Penerimaan Dana (Termin)',
            'tarik_bank' => 'Penarikan Bank ke Kas Tunai (UP)',
            'belanja_tunai' => 'Belanja / Pengeluaran Tunai',
            'belanja_transfer' => 'Belanja / Transfer Bank',
            default => ucfirst(str_replace('_', ' ', $this->jenis_transaksi)),
        };
    }

    /**
     * Badge CSS class untuk jenis transaksi
     */
    public function getJenisBadgeClassAttribute(): string
    {
        return match ($this->jenis_transaksi) {
            'terima_dana' => 'bg-success-subtle text-success border border-success-subtle',
            'tarik_bank' => 'bg-info-subtle text-info-emphasis border border-info-subtle',
            'belanja_tunai' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
            'belanja_transfer' => 'bg-primary-subtle text-primary border border-primary-subtle',
            default => 'bg-secondary-subtle text-secondary',
        };
    }

    /**
     * Posisi pencatatan pada ketiga buku (Langkah-langkah Pencatatan)
     */
    public function getPencatatanBukuInfoAttribute(): array
    {
        return match ($this->jenis_transaksi) {
            'terima_dana' => [
                'bb' => 'Penerimaan',
                'bpkt' => '-',
                'bku' => 'Penerimaan',
            ],
            'tarik_bank' => [
                'bb' => 'Pengeluaran',
                'bpkt' => 'Penerimaan',
                'bku' => '-',
            ],
            'belanja_tunai' => [
                'bb' => '-',
                'bpkt' => 'Pengeluaran',
                'bku' => 'Pengeluaran',
            ],
            'belanja_transfer' => [
                'bb' => 'Pengeluaran',
                'bpkt' => '-',
                'bku' => 'Pengeluaran',
            ],
            default => [
                'bb' => '-',
                'bpkt' => '-',
                'bku' => '-',
            ],
        };
    }
}
