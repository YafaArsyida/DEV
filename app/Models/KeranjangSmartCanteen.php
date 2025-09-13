<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangSmartCanteen extends Model
{
    use HasFactory;

    protected $table = 'ms_keranjang_smartcanteen'; // Nama tabel
    protected $primaryKey = 'ms_keranjang_smartcanteen_id'; // Nama kolom primary key

    protected $fillable = [
        'user_type',
        'user_id',
        'ms_produk_kantin_id',
        'ms_pengguna_id',
        'jumlah_produk',
    ];
    // Relasi ke siswa
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'ms_siswa_id')
            ->where('user_type', 'siswa');
    }

    // Relasi ke pegawai
    public function ms_pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'user_id', 'ms_pegawai_id')
            ->where('user_type', 'pegawai');
    }

    // Relasi ke produk kantin
    public function ms_produk_kantin()
    {
        return $this->belongsTo(ProdukSmartCanteen::class, 'ms_produk_kantin_id', 'ms_produk_kantin_id');
    }
}
