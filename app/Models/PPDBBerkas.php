<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBBerkas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_berkas';

    protected $primaryKey = 'ppdb_berkas_id';

    protected $fillable = [
        'ppdb_pendaftaran_id',
        'jenis_berkas',
        'nama_file',
        'path_file',
        'mime_type',
        'ukuran_file',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'ppdb_pendaftaran_id' => 'integer',
        'ukuran_file' => 'integer',
        'verified_by' => 'integer',
        'verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function ppdb_pendaftaran(): BelongsTo
    {
        return $this->belongsTo(
            PpdbPendaftaran::class,
            'ppdb_pendaftaran_id',
            'ppdb_pendaftaran_id'
        );
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by', 'id'
        );
    }
}
