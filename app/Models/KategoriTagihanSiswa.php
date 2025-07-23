<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriTagihanSiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_kategori_tagihan_siswa';
    protected $primaryKey = 'ms_kategori_tagihan_siswa_id';
    protected $fillable = [
        'ms_tahun_ajar_id',
        'ms_jenjang_id',
        'nama_kategori_tagihan_siswa',
        'urutan',
        'deskripsi',
    ];
    /**
     * Relasi ke model TahunAjar.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_tahun_ajar()
    {
        return $this->belongsTo(TahunAjar::class, 'ms_tahun_ajar_id', 'ms_tahun_ajar_id');
    }

    /**
     * Relasi ke model Jenjang.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }

    /**
     * Relasi ke `JenisTagihan`
     */
    public function ms_jenis_tagihan_siswa()
    {
        return $this->hasMany(JenisTagihanSiswa::class, 'ms_kategori_tagihan_siswa_id', 'ms_kategori_tagihan_siswa_id');
    }

    public function ms_tagihan_siswa()
    {
        return $this->hasManyThrough(
            TagihanSiswa::class,
            JenisTagihanSiswa::class,
            'ms_kategori_tagihan_siswa_id', // Foreign key di tabel JenisTagihan
            'ms_jenis_tagihan_siswa_id',    // Foreign key di tabel Tagihan
            'ms_kategori_tagihan_siswa_id', // Local key di tabel KategoriTagihan
            'ms_jenis_tagihan_siswa_id'     // Local key di tabel JenisTagihan
        );
    }

    /**
     * Total tagihan dari kategori ini
     */
    public function total_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa()->sum('jumlah_tagihan_siswa');
    }

    public function dt_transaksi_tagihan_siswa()
    {
        return DetailTransaksiTagihanSiswa::whereHas('ms_tagihan_siswa', function ($query) {
            $query->whereHas('ms_jenis_tagihan_siswa', function ($subQuery) {
                $subQuery->where('ms_kategori_tagihan_siswa_id', $this->ms_kategori_tagihan_siswa_id);
            });
        });
    }

    public function total_dibayarkan()
    {
        return $this->dt_transaksi_tagihan_siswa()->sum('jumlah_bayar');
    }
}
