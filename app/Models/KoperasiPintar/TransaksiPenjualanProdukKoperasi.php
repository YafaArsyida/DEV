<?php

namespace App\Models\KoperasiPintar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPenjualanProdukKoperasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ms_transaksi_penjualan_produk_koperasi';
    protected $primaryKey = 'ms_transaksi_penjualan_produk_koperasi_id';
    protected $fillable = [
        'user_type', // siswa / pegawai / umum
        'user_id',   // id siswa atau id pegawai, umum kosongi
        'ms_penempatan_siswa_id',
        'ms_pengguna_id', // kasir/operator
        'tanggal_transaksi',
        'total_transaksi',
        'metode_pembayaran', // tunai / edupay / transfer
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];
}
