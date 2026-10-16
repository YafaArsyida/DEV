<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBPertanyaan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_pertanyaan';

    protected $primaryKey = 'ppdb_pertanyaan_id';

    protected $fillable = [
        'ppdb_periode_id',
        'pertanyaan',
        'jenis',
        'opsi_jawaban',
        'wajib_diisi',
        'urutan',
        'status',
    ];

    protected $casts = [
        'opsi_jawaban' => 'array',
        'wajib_diisi' => 'boolean',
        'urutan' => 'integer',
    ];

    public function ppdb_periode(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPeriode::class,
            'ppdb_periode_id',
            'ppdb_periode_id'
        );
    }

    public function ppdb_jawaban(): HasMany
    {
        return $this->hasMany(
            PPDBJawaban::class,
            'ppdb_pertanyaan_id',
            'ppdb_pertanyaan_id'
        );
    }
}
