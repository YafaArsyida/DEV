<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagihanSiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ms_tagihan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_tagihan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_penempatan_siswa_id',
        'ms_jenis_tagihan_siswa_id',
        'ms_pengguna_id',
        'jumlah_tagihan_siswa',
        'status',       // Belum Dibayar, Masih Dicicil, Lunas
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
     * Relasi ke model Jenis Tagihan
     */
    public function ms_jenis_tagihan_siswa()
    {
        return $this->belongsTo(JenisTagihanSiswa::class, 'ms_jenis_tagihan_siswa_id', 'ms_jenis_tagihan_siswa_id');
    }

    /**
     * Scope untuk mengurutkan berdasarkan ms_kategori_tagihan_id
     */
    public function scopeOrderByKategori($query)
    {
        return $query->join('ms_jenis_tagihan_siswa', 'ms_tagihan_siswa.ms_jenis_tagihan_siswa_id', '=', 'ms_jenis_tagihan_siswa.ms_jenis_tagihan_siswa_id')
            ->join('ms_kategori_tagihan', 'ms_jenis_tagihan_siswa.ms_kategori_tagihan_id', '=', 'ms_kategori_tagihan.ms_kategori_tagihan_id')
            ->orderBy('ms_kategori_tagihan.ms_kategori_tagihan_id', 'ASC');
    }

    /**
     * Mendapatkan Nama Tahun Ajar dari Penempatan Siswa
     */
    public function nama_kategori_tagihan_siswa()
    {
        return $this->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? 'Kategori Tidak Ditemukan';
    }

    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    /**
     * Mendapatkan Nama Tahun Ajar dari Penempatan Siswa
     */
    public function nama_tahun_ajar()
    {
        return $this->ms_penempatan_siswa->ms_tahun_ajar->nama_tahun_ajar ?? 'Tahun Ajar Tidak Ditemukan';
    }
    /**
     * Mendapatkan Nama Tahun Ajar dari Penempatan Siswa
     */
    public function nama_jenjang()
    {
        return $this->ms_penempatan_siswa->ms_jenjang->nama_jenjang ?? 'Jenjang Tidak Ditemukan';
    }

    /**
     * Mendapatkan Nama Kelas dari Penempatan Siswa
     */
    public function nama_siswa()
    {
        return $this->ms_penempatan_siswa->ms_siswa->nama_siswa ?? 'Siswa Tidak Ditemukan';
    }
    /**
     * Mendapatkan Nama Kelas dari Penempatan Siswa
     */
    public function nama_kelas()
    {
        return $this->ms_penempatan_siswa->ms_kelas->nama_kelas ?? 'Kelas Tidak Ditemukan';
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
    public function jumlah_kekurangan()
    {
        return $this->jumlah_tagihan_siswa - $this->jumlah_sudah_dibayar();
    }

    // Relasi ke jurnal debit
    public function jurnalDebit()
    {
        return $this->belongsTo(AkuntansiJurnalDetail::class, 'akuntansi_jurnal_detail_debit_id', 'akuntansi_jurnal_detail_id');
    }

    // Relasi ke jurnal kredit
    public function jurnalKredit()
    {
        return $this->belongsTo(AkuntansiJurnalDetail::class, 'akuntansi_jurnal_detail_kredit_id', 'akuntansi_jurnal_detail_id');
    }
}
