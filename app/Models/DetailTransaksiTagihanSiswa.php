<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksiTagihanSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dt_transaksi_tagihan_siswa'; // Nama tabel
    protected $primaryKey = 'dt_transaksi_tagihan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_transaksi_tagihan_siswa_id',
        'ms_tagihan_siswa_id',
        'jumlah_bayar',
        'deskripsi',
    ];

    /**
     * Relasi ke model Transaksi
     */
    public function ms_transaksi_tagihan_siswa()
    {
        return $this->belongsTo(TransaksiTagihanSiswa::class, 'ms_transaksi_tagihan_siswa_id', 'ms_transaksi_tagihan_siswa_id');
    }

    /**
     * Relasi ke model Tagihan
     */
    public function ms_tagihan_siswa()
    {
        return $this->belongsTo(TagihanSiswa::class, 'ms_tagihan_siswa_id', 'ms_tagihan_siswa_id');
    }

    public function nama_jenis_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan';
    }
}
