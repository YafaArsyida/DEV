<?php

namespace App\Models\SmartPass;

use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresensiSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_absensi_siswa';
    protected $primaryKey = 'ms_absensi_siswa_id';

    protected $fillable = [
        'ms_siswa_id',
        'ms_penempatan_siswa_id',
        'ms_pengguna_id',
        'kode_kartu',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status_masuk',
        'status_pulang',
        'deskripsi',
    ];
    /**
     * Relasi ke model Siswa
     */
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'ms_siswa_id', 'ms_siswa_id');
    }
    /**
     * Relasi ke model Siswa
     */
    public function ms_penempatan_siswa()
    {
        return $this->belongsTo(PenempatanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }

    /**
     * Relasi ke model Petugas
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
}
