<?php

namespace App\Models\SmartCanteen;

use App\Models\Jenjang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kantin extends Model
{
    use HasFactory;

    protected $table = 'ms_kantin';
    protected $primaryKey = 'ms_kantin_id';
    protected $fillable = [
        'nama_kantin',
        'deskripsi',
    ];

    public function ms_pengguna()
    {
        return $this->belongsToMany(User::class, 'ms_akses_kantin', 'ms_kantin_id', 'ms_pengguna_id'
        );
    }
}
