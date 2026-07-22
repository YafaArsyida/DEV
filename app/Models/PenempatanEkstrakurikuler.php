<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenempatanEkstrakurikuler extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_penempatan_ekstrakurikuler'; // Nama tabel
    protected $primaryKey = 'ms_penempatan_ekstrakurikuler_id'; // Primary key

    protected $fillable = [
        'ms_ekstrakurikuler_id',       // ID ekstrakurikuler
        'ms_penempatan_siswa_id',       // ID Siswa
    ];
    /**
     * Relasi 
     */
    public function ms_penempatan_siswa()
    {
        return $this->belongsTo(
            PenempatanSiswa::class,
            'ms_penempatan_siswa_id',
            'ms_penempatan_siswa_id'
        );
    }

    public function ms_ekstrakurikuler()
    {
        return $this->belongsTo(
            Ekstrakurikuler::class,
            'ms_ekstrakurikuler_id',
            'ms_ekstrakurikuler_id'
        );
    }
}
