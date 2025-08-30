<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriProdukKantin extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_kategori_produk_kantin';
    protected $primaryKey = 'ms_kategori_produk_kantin_id';
    protected $fillable = [
        'ms_jenjang_id',
        'nama_kategori_produk_kantin',
        'icon',
        'deskripsi',
    ];

    /**
     * Relasi ke model Jenjang.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }
    /**
     * Relasi ke `produk kantin`
     */
    public function ms_produk_kantin()
    {
        return $this->hasMany(ProdukKantin::class, 'ms_kategori_produk_kantin_id', 'ms_kategori_produk_kantin_id');
    }
}
