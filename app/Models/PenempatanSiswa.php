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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (BELONGS TO)
    |--------------------------------------------------------------------------
    */
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 
            'ms_siswa_id',
            'ms_siswa_id'
        );
    }

    public function ms_penempatan_ekstrakurikuler()
    {
        return $this->hasOne(
            PenempatanEkstrakurikuler::class,
            'ms_penempatan_siswa_id',
            'ms_penempatan_siswa_id'
        );
    }
    
    public function ms_kelas()
    {
        return $this->belongsTo(Kelas::class, 
            'ms_kelas_id', 
            'ms_kelas_id'
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

    public function ms_pengguna()
    {
        return $this->belongsTo(User::class,
            'ms_pengguna_id',
            'ms_pengguna_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (HAS MANY)
    |--------------------------------------------------------------------------
    */
    public function ms_tagihan_siswa()
    {
        return $this->hasMany(TagihanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }

    public function ms_transaksi_tagihan_siswa()
    {
        return $this->hasMany(TransaksiTagihanSiswa::class, 'ms_penempatan_siswa_id', 'ms_penempatan_siswa_id');
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (ADVANCED)
    |--------------------------------------------------------------------------
    */
    public function dt_transaksi_tagihan_siswa()
    {
        return $this->hasManyThrough(
            DetailTransaksiTagihanSiswa::class,
            TransaksiTagihanSiswa::class,
            'ms_penempatan_siswa_id',          // FK di transaksi
            'ms_transaksi_tagihan_siswa_id',   // FK di detail
            'ms_penempatan_siswa_id',          // PK lokal
            'ms_transaksi_tagihan_siswa_id'    // PK transaksi
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AGGREGATIONS (RECOMMENDED: USE QUERY LEVEL)
    |--------------------------------------------------------------------------
    */

    public function total_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa->sum('jumlah_tagihan_siswa');
    }

    public function total_dibayarkan()
    {
        return $this->ms_tagihan_siswa->sum(function ($tagihan) {
            return $tagihan->jumlah_sudah_dibayar ?? 0;
        });
    }

    public function total_kekurangan()
    {
        return $this->total_tagihan_siswa() - $this->total_dibayarkan();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS / BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    public function jumlah_jenis_tagihan_siswa()
    {
        return $this->ms_tagihan_siswa()
            ->distinct('ms_jenis_tagihan_siswa_id')
            ->count('ms_jenis_tagihan_siswa_id');
    }

    public function sudahDinaikkan($tahunAjarBerikutId)
    {
        return self::where('ms_siswa_id', $this->ms_siswa_id)
            ->where('ms_tahun_ajar_id', $tahunAjarBerikutId)
            ->exists();
    }
}
