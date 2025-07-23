<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Ekstrakurikuler extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_ekstrakurikuler';
    protected $primaryKey = 'ms_ekstrakurikuler_id';
    protected $fillable = [
        'ms_jenjang_id',
        'nama_ekstrakurikuler',
        'deskripsi',
        'biaya',
        'kuota',
    ];

    /**
     * Relasi ke model Jenjang.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }

    public function ms_penempatan_ekstrakurikuler()
    {
        return $this->hasMany(PenempatanEkstrakurikuler::class, 'ms_ekstrakurikuler_id', 'ms_ekstrakurikuler_id');
    }

    /**
     * Menghitung jumlah siswa yang sudah ditempatkan di ekstrakurikuler ini.
     *
     * @return int
     */
    public function total_penempatan_siswa(): int
    {
        return $this->ms_penempatan_ekstrakurikuler()->count();
    }

    public function kuota_tersedia()
    {
        $kuota = (int) $this->kuota;
        $terisi = $this->total_penempatan_siswa();

        return max($kuota - $terisi, 0); // hasil tidak akan negatif
    }
}
