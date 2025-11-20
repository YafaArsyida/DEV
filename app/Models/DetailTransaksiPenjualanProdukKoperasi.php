<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenjualanProdukKoperasi extends Model
{
    use HasFactory;

    protected $table = 'dt_penjualan_produk_koperasi';
    protected $primaryKey = 'dt_penjualan_produk_koperasi_id';
    protected $fillable = [
        'ms_transaksi_penjualan_produk_koperasi_id',
        'ms_produk_koperasi_id',
        'jumlah_produk',
        'jumlah_bayar',
        'deskripsi',
    ];
}
