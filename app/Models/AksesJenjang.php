<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesJenjang extends Model
{
    use HasFactory;
    protected $table = 'ms_akses_jenjang'; // Nama tabel
    protected $primaryKey = 'ms_akses_jenjang_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_jenjang_id',
        'ms_pengguna_id',
    ];

    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }
}
