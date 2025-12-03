<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsensiPegawai extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_absensi_pegawai';
    protected $primaryKey = 'ms_absensi_pegawai_id';

    protected $fillable = [
        'ms_pegawai_id',
        'ms_pengguna_id',
        'kode_kartu',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status_masuk',
        'status_pulang',
        'deskripsi',
    ];
    // Relasi ke tabel ms_pegawai
    public function ms_pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'user_id', 'ms_pegawai_id');
    }

    // Relasi ke petugas yang menginputkan absensi manual
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
}
