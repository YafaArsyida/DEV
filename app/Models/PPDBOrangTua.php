<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBOrangTua extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_orangtua';

    protected $primaryKey = 'ppdb_orangtua_id';

    
    protected $fillable = [
        'ppdb_pendaftaran_id',

        // Data ayah
        'nama_ayah',
        'nik_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'berkebutuhan_khusus_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'telepon_ayah',

        // Data ibu
        'nama_ibu',
        'nik_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'berkebutuhan_khusus_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'telepon_ibu',

        // Data wali
        'nama_wali',
        'nik_wali',
        'tempat_lahir_wali',
        'tanggal_lahir_wali',
        'berkebutuhan_khusus_wali',
        'hubungan_wali',
        'pekerjaan_wali',
        'telepon_wali',
    ];


    protected $casts = [
        'ppdb_pendaftaran_id' => 'integer',
        'deleted_at' => 'datetime',

        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'tanggal_lahir_wali' => 'date',
    ];

    public function ppdb_pendaftaran(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPendaftaran::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }
}
