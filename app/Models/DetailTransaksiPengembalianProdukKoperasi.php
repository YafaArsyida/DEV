<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPengembalianProdukKoperasi extends Model
{
    use HasFactory;

    protected $table = 'dt_pengembalian_produk_koperasi';
    protected $primaryKey = 'dt_pengembalian_produk_koperasi_id';
    protected $fillable = [
        'ms_transaksi_pengembalian_produk_koperasi_id',
        'ms_produk_koperasi_id',
        'jumlah',
        'harga_beli',
        'subtotal',
    ];
}
