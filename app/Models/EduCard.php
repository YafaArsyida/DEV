<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EduCard extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_educard'; // Nama tabel
    protected $primaryKey = 'ms_educard_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_pengguna_id',
        'ms_pegawai_id',
        'ms_siswa_id',
        'kode_kartu',
        'jenis_pemilik',
        'status_kartu',
        'deskripsi',
    ];
}
