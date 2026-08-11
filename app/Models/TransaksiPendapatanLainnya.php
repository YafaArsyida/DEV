<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPendapatanLainnya extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'transaksi_pendapatan_lainnya'; // Nama tabel
    protected $primaryKey = 'transaksi_pendapatan_lainnya_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_pengguna_id',
        'ms_jenjang_id',
        'kode_rekening',
        'nominal',
        'metode_pembayaran',
        'tanggal',
        'deskripsi',
        'akuntansi_jurnal_id',
    ];
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    /**
     * Relasi ke model Jenjang
     */
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }

    /**
     * Relasi ke model akun rekening
     */
    public function akuntansi_rekening()
    {
        return $this->belongsTo(AkuntansiRekening::class, 'kode_rekening', 'kode_rekening');
    }

    public function akuntansi_jurnal()
    {
        return $this->belongsTo(AkuntansiJurnal::class, 'akuntansi_jurnal_id', 'akuntansi_jurnal_id');
    }
}
