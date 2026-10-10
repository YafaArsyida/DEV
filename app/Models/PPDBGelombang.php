<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBGelombang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ppdb_gelombang';
    protected $primaryKey = 'ppdb_gelombang_id'; // Nama kolom primary key

    protected $fillable = [
        'ppdb_periode_id',
        'nama_gelombang',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'biaya_pendaftaran',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai'     => 'date',
        'tanggal_selesai'   => 'date',
        'kuota'             => 'integer',
        'biaya_pendaftaran' => 'decimal:2',
    ];

    public function ppdb_periode()
    {
        return $this->belongsTo(
            PPDBPeriode::class, 'ppdb_periode_id', 'ppdb_periode_id'
        );
    }
}
