<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenempatanSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_penempatan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_penempatan_siswa_id'; // Primary key

    protected $fillable = [
        'ms_siswa_id',       // ID Siswa
        'ms_kelas_id',       // ID Kelas
        'ms_tahun_ajar_id',  // ID Tahun Ajar
        'ms_jenjang_id',     // ID Jenjang
        'ms_pengguna_id',     // ID Petugas
    ];
    /**
     * Relasi ke model Siswa
     */
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'ms_siswa_id', 'ms_siswa_id');
    }

    /**
     * Relasi ke model Kelas
     */
    public function ms_kelas()
    {
        return $this->belongsTo(Kelas::class, 'ms_kelas_id', 'ms_kelas_id');
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
     * Relasi ke model Petugas
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    /**
     * Relasi ke model Tagihan
     */
    public function ms_tagihan_siswa()
    {
        return $this->hasMany(TagihanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }

    // Mendapatkan jumlah jenis tagihan unik
    public function jumlah_jenis_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa()->distinct('ms_jenis_tagihan_siswa_id')->count('ms_jenis_tagihan_siswa_id');
    }

    // Cek apakah siswa sudah dinaikkan ke tahun ajar berikutnya
    public function sudahDinaikkan($tahunAjarBerikutId)
    {
        return self::where('ms_siswa_id', $this->ms_siswa_id)
            ->where('ms_tahun_ajar_id', $tahunAjarBerikutId)
            ->exists();
    }

    // relasi ke transaksi
    public function ms_transaksi_tagihan_siswa()
    {
        return $this->hasMany(TransaksiTagihanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }

    // Mendapatkan total tagihan berdasarkan ms_penempatan_siswa_id
    public function total_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa()->sum('jumlah_tagihan_siswa');
    }

    // jumlah sudah dibayar
    public function total_dibayarkan()
    {
        return DetailTransaksiTagihanSiswa::whereIn(
            'ms_transaksi_tagihan_siswa_id',
            $this->ms_transaksi_tagihan_siswa()->pluck('ms_transaksi_tagihan_siswa_id')
        )->sum('jumlah_bayar');
    }

    public function total_kekurangan()
    {
        $totalTagihanSiswa = $this->total_tagihan_siswa();
        $totalDibayarkan = $this->total_dibayarkan();

        return $totalTagihanSiswa - $totalDibayarkan;
    }

    // jumlah bayaran item 
    public function jumlah_sudah_dibayar()
    {
        return $this->ms_transaksi_tagihan_siswa->flatMap(function ($transaksi) {
            return $transaksi->dt_transaksi_tagihan_siswa;
        })->sum('jumlah_bayar');
    }
}
