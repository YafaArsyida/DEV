<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TransaksiSmartCanteen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_transaksi_kantin'; // Nama tabel
    protected $primaryKey = 'ms_transaksi_kantin_id'; // Nama kolom primary key

    protected $fillable = [
        'user_type',
        'user_id',
        'ms_penempatan_siswa_id',
        'ms_pengguna_id',
        'tanggal_transaksi',
        'total_transaksi',
        'metode_pembayaran',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];

     /**
     * Relasi ke model Pegawai
     */
    public function ms_pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'user_id', 'ms_pegawai_id');
    }

    /**
     * Relasi ke model Siswa
     */
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'ms_siswa_id');
    }

    /**
     * Relasi ke model Siswa
     */
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

    public function akuntansi_jurnal_detail()
    {
        return $this->belongsTo(AkuntansiJurnalDetail::class, 'akuntansi_jurnal_detail_id', 'akuntansi_jurnal_detail_id');
    }
    
    public function dt_transaksi_kantin()
    {
        return $this->hasMany(DetailTransaksiSmartCanteen::class, 'ms_transaksi_kantin_id');
    }
}
