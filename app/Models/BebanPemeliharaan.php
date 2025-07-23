<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BebanPemeliharaan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ms_beban_pemeliharaan'; // Nama tabel
    protected $primaryKey = 'ms_beban_pemeliharaan_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_pengguna_id',
        'ms_jenjang_id',
        'ms_tahun_ajar_id',
        // gedung, kendaraan, peralatan, lingkungan, lain-lain
        'jenis_beban_pemeliharaan',
        'nominal',
        'metode_pembayaran', //untuk mengurangi saldo kas atau bank
        'tanggal',
        'deskripsi',
        'akuntansi_jurnal_detail_debit_id',
        'akuntansi_jurnal_detail_kredit_id',
    ];

    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
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
}
