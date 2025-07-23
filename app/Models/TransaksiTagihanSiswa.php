<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiTagihanSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_transaksi_tagihan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_transaksi_tagihan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_penempatan_siswa_id',
        'ms_pengguna_id',
        'tanggal_transaksi',
        'metode_pembayaran',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];
    /**
     * Relasi ke model PenempatanSiswa
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
    /**
     * Relasi ke model DetailTransaksi
     */
    public function dt_transaksi_tagihan_siswa()
    {
        return $this->hasMany(DetailTransaksiTagihanSiswa::class, 'ms_transaksi_tagihan_siswa_id', 'ms_transaksi_tagihan_siswa_id');
    }

    public function akuntansi_jurnal_detail()
    {
        return $this->belongsTo(AkuntansiJurnalDetail::class, 'akuntansi_jurnal_detail_id', 'akuntansi_jurnal_detail_id');
    }
}
