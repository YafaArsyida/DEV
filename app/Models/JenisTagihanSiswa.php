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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (BELONGS TO)
    |--------------------------------------------------------------------------
    */

    public function ms_kategori_tagihan_siswa()
    {
        return $this->belongsTo(KategoriTagihanSiswa::class,
            'ms_kategori_tagihan_siswa_id',
            'ms_kategori_tagihan_siswa_id'
        );
    }

    public function ms_tahun_ajar()
    {
        return $this->belongsTo(TahunAjar::class,
            'ms_tahun_ajar_id',
            'ms_tahun_ajar_id'
        );
    }

    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class,
            'ms_jenjang_id',
            'ms_jenjang_id'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (HAS MANY)
    |--------------------------------------------------------------------------
    */

    public function ms_tagihan_siswa()
    {
        return $this->hasMany(TagihanSiswa::class,
            'ms_jenis_tagihan_siswa_id',
            'ms_jenis_tagihan_siswa_id'
        );
    }



    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (ADVANCED)
    |--------------------------------------------------------------------------
    */
    public function dt_transaksi_tagihan_siswa()
    {
        return $this->hasManyThrough(DetailTransaksiTagihanSiswa::class,
            TagihanSiswa::class,
            'ms_jenis_tagihan_siswa_id', // FK di tagihan
            'ms_tagihan_siswa_id',       // FK di detail
            'ms_jenis_tagihan_siswa_id', // PK lokal
            'ms_tagihan_siswa_id'        // PK tagihan
        );
    }



    /*
    |--------------------------------------------------------------------------
    | ACCESSOR / HELPER (SAFE FOR VIEW)
    |--------------------------------------------------------------------------
    */
    public function nama_kategori_tagihan_siswa()
    {
        return $this->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-';
    }



    /*
    |--------------------------------------------------------------------------
    | AGGREGATION (⚠️ JANGAN DIPAKAI DI LOOP BESAR)
    |--------------------------------------------------------------------------
    */
    public function jumlah_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa()->count();
    }

    public function total_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa()->sum('jumlah_tagihan_siswa');
    }

    public function total_tagihan_siswa_dibayarkan()
    {
        return $this->dt_transaksi_tagihan_siswa()->sum('jumlah_bayar');
    }

    public function total_kekurangan()
    {
        return $this->total_tagihan_siswa() - $this->total_tagihan_siswa_dibayarkan();
    }
}
