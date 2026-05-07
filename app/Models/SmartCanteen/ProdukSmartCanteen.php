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
        'ms_kantin_id',
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
     * Relasi ke model Kantin.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_kantin()
    {
        return $this->belongsTo(Kantin::class, 'ms_kantin_id', 'ms_kantin_id');
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
