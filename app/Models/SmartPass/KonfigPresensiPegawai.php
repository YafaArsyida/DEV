<?php

namespace App\Models\SmartPass;

use App\Models\Jenjang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigPresensiPegawai extends Model
{
    use HasFactory;

    protected $table = 'ms_absensi_pegawai_konfig';
    protected $primaryKey = 'ms_absensi_pegawai_konfig_id';

    protected $fillable = [
        'ms_jenjang_id',
        'hari',
        'jam_masuk_awal',
        'jam_masuk_akhir',
        'jam_pulang_awal',
        'jam_pulang_akhir',
        'toleransi_masuk_menit',
        'toleransi_pulang_menit',
        'status_aktif',
        'deskripsi',
    ];

    /**
     * Relasi ke model Jenjang
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }
    /**
     * Scope: Filter hari aktif
     */
    public function scopeAktifHari($query, $hari)
    {
        return $query->where('hari', $hari)->where('status_aktif', 1);
    }
}
