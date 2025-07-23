<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_jabatan'; // Nama tabel
    protected $primaryKey = 'ms_jabatan_id'; // Nama kolom primary key

    protected $fillable = [
        'nama_jabatan',       // Nama Jabatan
        'deskripsi',          // deskripsi
    ];

    public function ms_pegawai()
    {
        return $this->hasMany(Pegawai::class, 'ms_jabatan_id');
    }

    public function jumlah_pegawai()
    {
        return $this->ms_pegawai()->count();
    }
}
