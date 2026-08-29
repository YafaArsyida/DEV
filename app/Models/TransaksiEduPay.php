<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiEduPay extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_transaksi_edupay'; // Nama tabel
    protected $primaryKey = 'ms_transaksi_edupay_id'; // Nama kolom primary key

    protected $fillable = [
        'user_type', // siswa, pegawai dll
        'user_id',   // id dari user_type terkait
        'ms_penempatan_siswa_id',
        'ms_pengguna_id',
        'jenis_transaksi',
        'nominal',
        'tanggal',
        'deskripsi',
        
        'akuntansi_jurnal_id',
        'akuntansi_jurnal_reversal_id',

        'status_transaksi'
        
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
    public function akuntansi_jurnal()
    {
        return $this->belongsTo(AkuntansiJurnal::class, 'akuntansi_jurnal_id', 'akuntansi_jurnal_id');
    }

    public function akuntansi_jurnal_reversal()
    {
        return $this->belongsTo(AkuntansiJurnal::class, 'akuntansi_jurnal_reversal_id', 'akuntansi_jurnal_id');
    }
}
