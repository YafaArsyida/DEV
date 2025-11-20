<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukKoperasi extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'ms_produk_koperasi';
    protected $primaryKey = 'ms_produk_koperasi_id';
    protected $fillable = [
        'ms_jenjang_id',
        'ms_pengguna_id',
        'ms_kategori_produk_koperasi_id',
        'kode_produk_koperasi',
        'nama_produk_koperasi',
        'satuan',
        'stok',
        'harga_beli',
        'harga_jual',
        'status_produk_koperasi', // aktif/nonaktif
        'deskripsi',
    ];
    public function keuntungan()
    {
        return $this->harga_jual - $this->harga_beli;
    }

    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }

    public function ms_kategori_produk_koperasi()
    {
        return $this->belongsTo(KategoriProdukKoperasi::class, 'ms_kategori_produk_koperasi_id');
    }
}
