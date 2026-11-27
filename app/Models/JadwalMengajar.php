<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalMengajar extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'ej_jadwal_mengajar';

    protected $primaryKey = 'ej_jadwal_mengajar_id';

    protected $fillable = [
        'ms_penugasan_guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function ms_penugasan_guru()
    {
        return $this->belongsTo(
            PenugasanGuru::class,
            'ms_penugasan_guru_id',
            'ms_penugasan_guru_id'
        );
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'AKTIF');
    }

    public function scopeHari($query, string $hari)
    {
        return $query->where('hari', strtoupper($hari));
    }
}
