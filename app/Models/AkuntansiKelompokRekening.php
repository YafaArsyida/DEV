<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AkuntansiKelompokRekening extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'akuntansi_kelompok_rekening'; // Nama tabel
    protected $primaryKey = 'akuntansi_kelompok_rekening_id'; // Nama kolom primary key

    protected $fillable = [
        'akuntansi_kelompok_rekening_id',
        'nama_kelompok_rekening',
        'deskripsi',
    ];

    public function akuntansi_rekening()
    {
        return $this->hasMany(AkuntansiRekening::class, 'akuntansi_kelompok_rekening_id', 'akuntansi_kelompok_rekening_id');
    }
}
