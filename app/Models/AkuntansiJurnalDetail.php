<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AkuntansiJurnalDetail extends Model
{
    use SoftDeletes;

    protected $table = 'akuntansi_jurnal_detail';

    protected $primaryKey = 'akuntansi_jurnal_detail_id';

    protected $fillable = [
        'akuntansi_jurnal_id',
        'kode_rekening',
        'posisi',
        'nominal',
    ];

    public function akuntansi_jurnal()
    {
        return $this->belongsTo(
            AkuntansiJurnal::class,
            'akuntansi_jurnal_id',
            'akuntansi_jurnal_id'
        );
    }
    /**
     * Relasi ke model Rekening
     */
    public function akuntansi_rekening()
    {
        return $this->belongsTo(AkuntansiRekening::class, 'kode_rekening', 'kode_rekening');
    }
    /**
     * Hitung total pendapatan
     */
    public function totalPendapatan()
    {
        return $this->whereHas('ms_akuntansi_rekening', function ($query) {
            $query->where('akuntansi_kelompok_rekening_id', 4);
        })
            ->where('posisi', 'debit')
            ->sum('nominal');
    }
    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
    public function ms_transaksi_tagihan_siswa()
    {
        return $this->hasOne(TransaksiTagihanSiswa::class, 'akuntansi_jurnal_detail_id', 'akuntansi_jurnal_detail_id');
    }

    public function ms_tagihan_siswa()
    {
        return $this->hasOne(TagihanSiswa::class, 'akuntansi_jurnal_detail_id', 'akuntansi_jurnal_detail_id');
    }
}
