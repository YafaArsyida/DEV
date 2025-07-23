<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanaBOS extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_dana_bos'; // Nama tabel
    protected $primaryKey = 'ms_dana_bos_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_pengguna_id',
        'ms_jenjang_id',
        'ms_tahun_ajar_id',
        'jenis_dana',
        'nominal',
        'metode_pembayaran',
        'tanggal',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
    /**
     * Relasi ke model TahunAjar
     */
    public function ms_tahun_ajar()
    {
        return $this->belongsTo(TahunAjar::class, 'ms_tahun_ajar_id', 'ms_tahun_ajar_id');
    }

    /**
     * Relasi ke model Jenjang
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }
}
