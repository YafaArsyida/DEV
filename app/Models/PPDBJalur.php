<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBJalur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ppdb_jalur';

    protected $primaryKey = 'ppdb_jalur_id';

    protected $fillable = [
        'ppdb_periode_id',
        'kode_jalur',
        'nama_jalur',
        'deskripsi',
        'kuota',
        'biaya_pendaftaran',
        'status',
    ];

    protected $casts = [
        'kuota' => 'integer',
        'biaya_pendaftaran' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    public function ppdb_periode(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPeriode::class,
            'ppdb_periode_id',
            'ppdb_periode_id'
        );
    }

    public function ppdb_pendaftaran(): HasMany
    {
        return $this->hasMany(
            PPDBPendaftaran::class, 'ppdb_jalur_id', 'ppdb_jalur_id'
        );
    }
}
