<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBPendaftaran extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_pendaftaran';

    protected $primaryKey = 'ppdb_pendaftaran_id';

    protected $fillable = [
        'ms_pengguna_id',
        'ppdb_periode_id',
        'ppdb_gelombang_id',
        'ppdb_jalur_id',
        'ms_siswa_id',
        'nomor_pendaftaran',
        'current_step',
        'status',
        'submitted_at',
        'submitted_by',

        'catatan_perbaikan'
    ];

    protected $casts = [
        'current_step' => 'integer',
        'submitted_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Model Lain
    |--------------------------------------------------------------------------
    */

    public function ms_pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    public function ppdb_periode(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPeriode::class, 'ppdb_periode_id', 'ppdb_periode_id'
        );
    }

    public function ppdb_gelombang(): BelongsTo
    {
        return $this->belongsTo(
            PPDBGelombang::class, 'ppdb_gelombang_id', 'ppdb_gelombang_id'
        );
    }

    public function ppdb_jalur(): BelongsTo
    {
        return $this->belongsTo(
            PPDBJalur::class, 'ppdb_jalur_id', 'ppdb_jalur_id'
        );
    }

    public function ms_siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class, 'ms_siswa_id', 'ms_siswa_id'
        );
    }

    public function ppdb_calon_siswa(): HasOne
    {
        return $this->hasOne(
            PPDBCalonSiswa::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }

    public function ppdb_orangtua(): HasOne
    {
        return $this->hasOne(
            PPDBOrangTua::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }

    public function ppdb_alamat(): HasOne
    {
        return $this->hasOne(
            PPDBAlamat::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }

    public function ppdb_pendidikan(): HasOne
    {
        return $this->hasOne(
            PPDBPendidikan::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }

    public function ppdb_berkas(): HasMany
    {
        return $this->hasMany(
            PPDBBerkas::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }
}
