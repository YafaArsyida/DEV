<?php

namespace App\Models\SmartCanteen;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesKantin extends Model
{
    use HasFactory;
    protected $table = 'ms_akses_kantin'; // Nama tabel
    protected $primaryKey = 'ms_akses_kantin_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_kantin_id',
        'ms_pengguna_id',
        'deskripsi',
    ];

    public function ms_kantin()
    {
        return $this->belongsTo(Kantin::class, 'ms_kantin_id', 'ms_kantin_id');
    }
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
}
