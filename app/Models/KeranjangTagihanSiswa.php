<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangTagihanSiswa extends Model
{
    use HasFactory;

    protected $table = 'ms_keranjang_tagihan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_keranjang_tagihan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_penempatan_siswa_id',
        'ms_tagihan_siswa_id',
        'ms_pengguna_id',
        'jumlah_bayar',
        'tanggal_dibayar',
        'status',       // Belum Dibayar, Masih Dicicil, Lunas
        'deskripsi',
    ];

    /**
     * Relasi ke model PenempatanSiswa
     */
    public function ms_penempatan_siswa()
    {
        return $this->belongsTo(PenempatanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }
    /**
     * Relasi ke model Tagihan
     */
    public function ms_tagihan_siswa()
    {
        return $this->belongsTo(TagihanSiswa::class, 'ms_tagihan_siswa_id', 'ms_tagihan_siswa_id');
    }

    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
    /**
     * Mendapatkan Nama Jenis Tgaihan
     */
    public function nama_jenis_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan';
    }
    /**
     * Mendapatkan Nama Nominal Tgaihan
     */
    public function jumlah_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa->jumlah_tagihan_siswa ?? 'Tidak Ditemukan';
    }
    /**
     * Mendapatkan Nama Kategori Tgaihan
     */
    public function nama_kategori_tagihan()
    {
        return $this->ms_tagihan_siswa->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan ?? 'Kategori Tidak Ditemukan';
    }
    /**
     * Relasi ke model Detail Transaksi
     */
    public function dt_transaksi_tagihan_siswa()
    {
        return $this->hasMany(DetailTransaksiTagihanSiswa::class, 'ms_tagihan_siswa_id', 'ms_tagihan_siswa_id');
    }

    /**
     * Mendapatkan jumlah yang sudah dibayarkan
     */
    public function jumlah_sudah_dibayar()
    {
        return $this->dt_transaksi_tagihan_siswa()->sum('jumlah_bayar');
    }
}
