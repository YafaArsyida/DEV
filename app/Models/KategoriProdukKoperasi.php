<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriProdukKoperasi extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'ms_kategori_produk_koperasi';
    protected $primaryKey = 'ms_kategori_produk_koperasi_id';
    protected $fillable = [
        'ms_jenjang_id',
        'nama_kategori_produk_koperasi',
        'status_kategori_produk_koperasi', // aktif/nonaktif
        'deskripsi',
    ];
}
