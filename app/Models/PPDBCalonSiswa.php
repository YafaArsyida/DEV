<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PPDBCalonSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ppdb_calon_siswa';

    protected $primaryKey = 'ppdb_calon_siswa_id';

    protected $fillable = [
        'ppdb_pendaftaran_id',

        // Identitas siswa
        'nama_siswa',
        'nama_panggilan',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'kewarganegaraan',
        'anak_ke',

        // Data keluarga
        'jumlah_saudara_kandung',
        'jumlah_saudara_tiri',
        'status_yatim_piatu',

        // Data pribadi
        'cita_cita',
        'hobi',
        'berkebutuhan_khusus',
        'penyakit',
        'alergi',

        // Kondisi fisik
        'tinggi_badan',
        'berat_badan',
        'ukuran_baju',

        // Bantuan sosial
        'penerima_bantuan_sosial',
        'nomor_bantuan_sosial',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'anak_ke' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    public function ppdb_pendaftaran(): BelongsTo
    {
        return $this->belongsTo(
            PPDBPendaftaran::class, 'ppdb_pendaftaran_id', 'ppdb_pendaftaran_id'
        );
    }
}
