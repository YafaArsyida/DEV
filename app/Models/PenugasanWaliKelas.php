<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenugasanWaliKelas extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'ms_penugasan_wali_kelas';

    protected $primaryKey = 'ms_penugasan_wali_kelas_id';

    protected $fillable = [
        'ms_pegawai_id',
        'ms_kelas_id',
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

    public function ms_tahun_ajar()
    {
        return $this->belongsTo(
            TahunAjar::class,
            'ms_tahun_ajar_id',
            'ms_tahun_ajar_id'
        );
    }
}
