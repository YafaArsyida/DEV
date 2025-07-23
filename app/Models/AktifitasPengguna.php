<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AktifitasPengguna extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_aktifitas_pengguna'; // Nama tabel
    protected $primaryKey = 'ms_aktifitas_pengguna_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_pengguna_id',
        'ms_jenjang_id',
        'ms_tahun_ajar_id',
        'tipe_aksi',
        'tipe_tabel',
        'id_tabel',
        'ip_pengguna',
        'perangkat_pengguna',
        'deskripsi',
    ];

    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id');
    }

    public function ms_tahun_ajar()
    {
        return $this->belongsTo(TahunAjar::class, 'ms_tahun_ajar_id');
    }
}
