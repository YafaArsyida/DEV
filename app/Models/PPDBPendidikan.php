<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBPendidikan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_pendidikan';

    protected $primaryKey = 'ppdb_pendidikan_id';

    protected $fillable = [
        'ppdb_pendaftaran_id',
        'asal_sekolah',
        'alamat_asal_sekolah',
        'npsn_asal_sekolah',
        'jenjang_asal',
        'tahun_lulus',
        'nomor_ijazah',
    ];

    protected $casts = [
        'ppdb_pendaftaran_id' => 'integer',
        'tahun_lulus' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function ppdb_pendaftaran(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPendaftaran::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }
}
