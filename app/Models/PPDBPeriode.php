<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBPeriode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ppdb_periode';
    protected $primaryKey = 'ppdb_periode_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_tahun_ajar_id',
        'ms_jenjang_id',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function ms_tahun_ajar()
    {
        return $this->belongsTo(
            TahunAjar::class, 'ms_tahun_ajar_id', 'ms_tahun_ajar_id'
        );
    }

    public function ms_jenjang()
    {
        return $this->belongsTo(
            Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id'
        );
    }
    public function ppdb_gelombang()
    {
        return $this->hasMany(
            PPDBGelombang::class, 'ppdb_periode_id', 'ppdb_periode_id'
        );
    }
}
