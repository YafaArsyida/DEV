<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_pegawai'; // Nama tabel
    protected $primaryKey = 'ms_pegawai_id'; // Nama kolom primary key

    protected $fillable = [
        'nama_pegawai',   // Nama Pegawai
        'nip',            // Nomor Induk Pegawai
        'ms_jabatan_id',  // ID Jabatan
        'ms_jenjang_id',  // ID Jenjang
        'ms_pengguna_id', // ID Pengguna
        'email',          // Email Pegawai
        'telepon',        // Nomor Telepon Pegawai
        'alamat',         // Alamat Pegawai
        'deskripsi',      // Deskripsi Tambahan
    ];

    /**
     * Relasi ke model Petugas
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    /**
     * Relasi ke model Jabatan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'ms_jabatan_id', 'ms_jabatan_id');
    }

    /**
     * Relasi ke model Jenjang
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }

    /**
     * Relasi ke model EduCard
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ms_educard()
    {
        return $this->hasOne(EduCard::class, 'ms_pegawai_id', 'ms_pegawai_id')
            ->where('jenis_pemilik', 'pegawai');
    }
}
