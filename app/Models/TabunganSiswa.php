<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabunganSiswa extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_tabungan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_tabungan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_penempatan_siswa_id',
        'ms_siswa_id',
        'ms_pengguna_id',
        'jenis_transaksi',
        'nominal',
        'tanggal',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];

    public function ms_penempatan_siswa()
    {
        return $this->belongsTo(PenempatanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }

    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
    /**
     * Relasi ke model Pengguna
     */
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'ms_siswa_id', 'ms_siswa_id');
    }
}
