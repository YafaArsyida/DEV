<?php

namespace App\Models\FingerSpot;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiPegawaiLog extends Model
{
    protected $table = 'presensi_pegawai_log';

    protected $fillable = [
        'type',
        'cloud_id',
        'pin',
        'scan_time',
        'verify_method',
        'status_scan',
        'raw'
    ];

    protected $casts = [
        'scan_time' => 'datetime',
        'raw'       => 'array'
    ];

    public function ms_pegawai()
    {
        // relasi ke pegawai berdasarkan pin_fingerspot
        return $this->belongsTo(Pegawai::class, 'pin', 'pin_fingerspot');
    }
}
