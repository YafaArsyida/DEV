<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPengembalianBarangKoperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ms_transaksi_pengembalian_produk_koperasi';
    protected $primaryKey = 'ms_transaksi_pengembalian_produk_koperasi_id';
    protected $fillable = [
        'ms_transaksi_pengembalian_produk_koperasi_id',
        // 'ms_supplier_koperasi_id', ambil dari transaksi
        'ms_pengguna_id',
        'tanggal_return',
        'total_return',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];
}
