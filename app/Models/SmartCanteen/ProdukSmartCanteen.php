<?php

namespace App\Models\SmartCanteen;

use App\Models\Jenjang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukSmartCanteen extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ms_produk_kantin';
    protected $primaryKey = 'ms_produk_kantin_id';
    protected $fillable = [
        'ms_jenjang_id',
        'ms_pengguna_id',
        'ms_kategori_produk_kantin_id',
        'nama_produk_kantin',
        'harga',
        'stok',
        'satuan',
        'status',
        'deskripsi',
        'icon',
        'icon_color',
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

    public function ms_kategori_produk_kantin()
    {
        return $this->belongsTo(KategoriProdukSmartCanteen::class, 'ms_kategori_produk_kantin_id');
    }
    /**
     * Relasi ke model Petugas
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
}
