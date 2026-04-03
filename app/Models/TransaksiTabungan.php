<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiTabungan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_transaksi_tabungan';
    protected $primaryKey = 'ms_transaksi_tabungan_id';

    protected $fillable = [
        'user_id',
        'user_type',
        'ms_penempatan_siswa_id',
        'ms_pengguna_id',
        'jenis_transaksi',
        'nominal',
        'tanggal',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];
    
    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
    // Relasi ke Siswa
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'ms_siswa_id');
        // ->where('user_type', 'siswa');
    }
    public function ms_penempatan_siswa()
    {
        return $this->belongsTo(PenempatanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }

    // Relasi ke Pegawai
    public function ms_pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'user_id', 'ms_pegawai_id');
        // ->where('user_type', 'pegawai');
    }
}
