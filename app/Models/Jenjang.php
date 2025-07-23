<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jenjang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_jenjang';
    protected $primaryKey = 'ms_jenjang_id';
    protected $fillable = [
        'nama_jenjang',
        'urutan',
        'status',
        'deskripsi',
    ];

    public function ms_pengguna(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ms_akses_jenjang', 'ms_jenjang_id', 'ms_pengguna_id')
            ->withTimestamps();
    }
}
