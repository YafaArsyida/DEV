<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBJawaban extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_jawaban';

    protected $primaryKey = 'ppdb_jawaban_id';

    protected $fillable = [
        'ppdb_periode_id',
        'ppdb_pendaftaran_id',
        'ppdb_pertanyaan_id',
        'jawaban',
    ];

    public function ppdb_periode(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPeriode::class,
            'ppdb_periode_id',
            'ppdb_periode_id'
        );
    }

    public function ppdb_pendaftaran(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPendaftaran::class,
            'ppdb_pendaftaran_id',
            'ppdb_pendaftaran_id'
        );
    }

    public function ppdb_pertanyaan(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPertanyaan::class,
            'ppdb_pertanyaan_id',
            'ppdb_pertanyaan_id'
        );
    }
}
