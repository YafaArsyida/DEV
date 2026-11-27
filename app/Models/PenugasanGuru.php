<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenugasanGuru extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_penugasan_guru';

    protected $primaryKey = 'ms_penugasan_guru_id';

    protected $fillable = [
        'ms_pegawai_id',
        'ms_kelas_id',
        'ms_mata_pelajaran_id',
        'ms_tahun_ajar_id',
        'status',
        'deskripsi',
    ];

    public function ms_pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'ms_pegawai_id',
            'ms_pegawai_id'
        );
    }

    public function ms_kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'ms_kelas_id',
            'ms_kelas_id'
        );
    }

    public function ms_mata_pelajaran()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'ms_mata_pelajaran_id',
            'ms_mata_pelajaran_id'
        );
    }

    public function ms_tahun_ajar()
    {
        return $this->belongsTo(
            TahunAjar::class,
            'ms_tahun_ajar_id',
            'ms_tahun_ajar_id'
        );
    }

    public function jadwal_mengajar()
    {
        return $this->hasMany(
            JadwalMengajar::class,
            'ms_penugasan_guru_id',
            'ms_penugasan_guru_id'
        );
    }
}
