<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAjar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_tahun_ajar';
    protected $primaryKey = 'ms_tahun_ajar_id';
    protected $fillable = [
        'nama_tahun_ajar',
        'urutan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
        'beranda',
        'tutup_buku',
        'tanggal_tutup_buku',
        'ms_pengguna_id',
    ];

    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
}
