<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBAlamat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_alamat';

    protected $primaryKey = 'ppdb_alamat_id';

    protected $fillable = [
        'ppdb_pendaftaran_id',
        'alamat_lengkap',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan_desa',
        'rt',
        'rw',
        'kode_pos',
        'jenis_tempat_tinggal',

        // Informasi perjalanan ke sekolah
        'jarak_ke_sekolah',
        'waktu_tempuh_ke_sekolah',
        'moda_transportasi',
    ];

    protected $casts = [
        'ppdb_pendaftaran_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function ppdb_pendaftaran(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPendaftaran::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }
}
