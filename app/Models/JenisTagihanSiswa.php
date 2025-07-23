<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTagihanSiswa extends Model
{
    use HasFactory;

    protected $table = 'ms_jenis_tagihan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_jenis_tagihan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_tahun_ajar_id',  // ID Tahun Ajar
        'ms_jenjang_id',
        'ms_kategori_tagihan_siswa_id',
        'nama_jenis_tagihan_siswa',
        'tanggal_jatuh_tempo',
        'deskripsi',
        'cicilan_status',
    ];

    /**
     * Relasi ke model KategoriTagihan
     */
    public function ms_kategori_tagihan_siswa()
    {
        return $this->belongsTo(KategoriTagihanSiswa::class, 'ms_kategori_tagihan_siswa_id', 'ms_kategori_tagihan_siswa_id');
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

    /**
     * Relasi ke model Tagihan
     */
    public function ms_tagihan_siswa()
    {
        return $this->hasMany(TagihanSiswa::class, 'ms_jenis_tagihan_siswa_id', 'ms_jenis_tagihan_siswa_id');
    }

    /**
     * Mendapatkan Nama Tahun Ajar dari Penempatan Siswa
     */
    public function nama_kategori_tagihan_siswa()
    {
        return $this->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? 'Tidak Ditemukan';
    }

    /**
     * Menghitung jumlah siswa yang terkait dengan jenis tagihan ini
     *
     * @return int
     */
    public function jumlah_tagihan_siswa()
    {
        return $this->hasMany(TagihanSiswa::class, 'ms_jenis_tagihan_siswa_id', 'ms_jenis_tagihan_siswa_id')->count();
    }

    public function dt_transaksi_tagihan_siswa()
    {
        return $this->hasManyThrough(
            DetailTransaksiTagihanSiswa::class,
            TagihanSiswa::class,
            'ms_jenis_tagihan_siswa_id', // Foreign key di tabel Tagihan
            'ms_tagihan_siswa_id',       // Foreign key di tabel DetailTransaksi
            'ms_jenis_tagihan_siswa_id', // Local key di tabel JenisTagihan
            'ms_tagihan_siswa_id'        // Local key di tabel Tagihan
        );
    }

    /**
     * Menghitung total nilai tagihan untuk jenis tagihan ini
     *
     * @return float
     */
    public function total_tagihan_siswa()
    {
        return $this->hasMany(TagihanSiswa::class, 'ms_jenis_tagihan_siswa_id', 'ms_jenis_tagihan_siswa_id')->sum('jumlah_tagihan_siswa');
    }

    public function total_tagihan_siswa_dibayarkan()
    {
        return $this->dt_transaksi_tagihan_siswa()->sum('jumlah_bayar');
    }

    public function total_kekurangan()
    {
        return $this->total_tagihan_siswa() - $this->total_dibayarkan();
    }
}
