<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevitalisasiTransaksiItem extends Model
{
    use HasFactory;

    protected $table = 'revitalisasi_transaksi_items';

    protected $fillable = [
        'revitalisasi_transaksi_id',
        'uraian_item',
        'jenis_biaya',
        'volume',
        'satuan',
        'harga_satuan',
        'subtotal',
        'urutan',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(RevitalisasiTransaksi::class, 'revitalisasi_transaksi_id');
    }
}
