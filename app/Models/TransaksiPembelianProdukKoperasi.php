<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPengembalianProdukKoperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ms_transaksi_pembelian_produk_koperasi';
    protected $primaryKey = 'ms_transaksi_pembelian_produk_koperasi_id';
    protected $fillable = [
        'ms_supplier_koperasi_id',
        'ms_pengguna_id', // user login yg input
        'tanggal_pembelian',
        'total_pembelian',
        'metode_pembayaran',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];
}
