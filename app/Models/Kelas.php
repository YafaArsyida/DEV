<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_kelas';
    protected $primaryKey = 'ms_kelas_id';
    protected $fillable = [
        'nama_kelas',
        'ms_jenjang_id',
        'ms_tahun_ajar_id',
        'urutan',
        'deskripsi',
        'status',
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

    public function ms_penempatan_siswa()
    {
        return $this->hasMany(PenempatanSiswa::class, 'ms_kelas_id');
    }

    public function jumlah_siswa()
    {
        return $this->ms_penempatan_siswa()->count();
    }

    public function totalEstimasi()
    {
        return $this->ms_penempatan_siswa()
            ->join('ms_tagihan', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tagihan.ms_penempatan_siswa_id')
            ->sum('ms_tagihan.jumlah_tagihan');
    }

    public function totalDibayarkan()
    {
        return $this->ms_penempatan_siswa()
            ->join('ms_tagihan', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tagihan.ms_penempatan_siswa_id')
            ->join('dt_transaksi', 'dt_transaksi.ms_tagihan_id', '=', 'ms_tagihan.ms_tagihan_id')
            ->sum('dt_transaksi.jumlah_bayar');
    }

    public function totalKekurangan()
    {
        $totalEstimasi = $this->totalEstimasi();
        $totalDibayarkan = $this->totalDibayarkan();

        return $totalEstimasi - $totalDibayarkan;
    }
}
