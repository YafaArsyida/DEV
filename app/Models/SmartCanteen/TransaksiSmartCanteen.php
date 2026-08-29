<?php

namespace App\Models\SmartCanteen;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\Pegawai;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TransaksiSmartCanteen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_transaksi_kantin'; // Nama tabel
    protected $primaryKey = 'ms_transaksi_kantin_id'; // Nama kolom primary key

    protected $fillable = [
        'user_type',
        'user_id',
        'ms_jenjang_id',

        'ms_pengguna_id',
        'ms_kantin_id',

        'tanggal_transaksi',
        'total_transaksi',
        'metode_pembayaran',

        'deskripsi',
        
        'akuntansi_jurnal_id',
        'akuntansi_jurnal_reversal_id',

        'status_settlement',
        'ms_settlement_kantin_id',

        'status_transaksi'
    ];

    /**
     * Relasi ke model Pegawai
     */
    public function ms_pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'user_id', 'ms_pegawai_id');
    }

    /**
     * Relasi ke model Siswa
     */
    public function ms_siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'ms_siswa_id');
    }

    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    public function akuntansi_jurnal()
    {
        return $this->belongsTo(AkuntansiJurnal::class, 'akuntansi_jurnal_id', 'akuntansi_jurnal_id');
    }
    
    public function akuntansi_jurnal_reversal()
    {
        return $this->belongsTo(AkuntansiJurnal::class, 'akuntansi_jurnal_reversal_id', 'akuntansi_jurnal_id');
    }

    public function dt_transaksi_kantin()
    {
        return $this->hasMany(DetailTransaksiSmartCanteen::class, 'ms_transaksi_kantin_id');
    }
    public function ms_jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'ms_jenjang_id', 'ms_jenjang_id');
    }
}
