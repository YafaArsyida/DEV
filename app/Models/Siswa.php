<?php

namespace App\Models;

use App\Models\SmartCanteen\TransaksiSmartCanteen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_siswa'; // Nama tabel
    protected $primaryKey = 'ms_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'nama_siswa',       // Nama Siswa
        'nisn',             // Nomor Induk Siswa Nasional
        'tempat_lahir',     // Tempat Lahir
        'tanggal_lahir',    // Tanggal Lahir
        'jenis_kelamin',    // Jenis Kelamin
        'alamat',           // Alamat
        'nama_ayah',        // Nama Ayah
        'nama_ibu',         // Nama Ibu
        'telepon',          // Telepon
        'deskripsi',          // deskripsi
    ];

    // Model Siswa
    public function ms_penempatan_siswa()
    {
        return $this->hasMany(PenempatanSiswa::class, 'ms_siswa_id', 'ms_siswa_id');
    }

    public function ms_penempatan_ekstrakurikuler()
    {
        return $this->hasMany(PenempatanEkstrakurikuler::class, 'ms_siswa_id', 'ms_siswa_id');
    }

    public function jumlah_ekstrakurikuler_diikuti()
    {
        return $this->ms_penempatan_ekstrakurikuler()->count();
    }

    public function total_biaya_ekstrakurikuler()
    {
        return $this->ms_penempatan_ekstrakurikuler
            ->sum(function ($penempatan) {
                return $penempatan->ms_ekstrakurikuler->biaya ?? 0;
            });
    }


    // Relasi ke Tagihan
    public function ms_tagihan_siswa()
    {
        return $this->hasManyThrough(TagihanSiswa::class, PenempatanSiswa::class, 'ms_siswa_id', 'ms_penempatan_siswa_id', 'ms_siswa_id', 'ms_penempatan_siswa_id');
    }

    public function ms_saldo_tabungan()
    {
        return $this->hasOne(SaldoTabungan::class, 'user_id', 'ms_siswa_id')
            ->where('user_type', 'siswa');
    }

    public function ms_transaksi_tabungan()
    {
        return $this->hasMany(TransaksiTabungan::class, 'user_id', 'ms_siswa_id')
            ->where('user_type', 'siswa');
    }
    
    public function total_kredit_tabungan()
    {
        // Menghitung total nominal kredit (Setoran)
        return $this->ms_transaksi_tabungan()
            ->where('jenis_transaksi', 'setoran')
            ->sum('nominal');
    }

    public function total_debit_tabungan()
    {
        return $this->ms_transaksi_tabungan()
            ->where('jenis_transaksi', 'penarikan')
            ->sum('nominal');
    }

    // /**
    //  * Total saldo terakhir untuk siswa ini
    //  */
    public function saldo_tabungan_siswa()
    {
        return $this->total_kredit_tabungan() - $this->total_debit_tabungan();
    }

    // =============== EDUPAY ===============
    public function ms_transaksi_edupay()
    {
        return $this->hasMany(TransaksiEduPay::class, 'user_id', 'ms_siswa_id')
            ->where('user_type', 'siswa');
    }
    public function total_pemasukan_edupay()
    {
        return $this->ms_transaksi_edupay()
            ->whereIn('jenis_transaksi', ['topup tunai', 'topup online', 'pengembalian dana'])
            ->sum('nominal');
    }

    public function total_penarikan_edupay()
    {
        return $this->ms_transaksi_edupay()
            ->where('jenis_transaksi', 'penarikan')
            ->sum('nominal');
    }

    public function total_pembayaran_edupay()
    {
        return $this->ms_transaksi_edupay()
            ->where('jenis_transaksi', 'pembayaran')
            ->sum('nominal');
    }
    public function total_pembayaran_kantin()
    {
        return $this->ms_transaksi_edupay()
            ->where('jenis_transaksi', 'kantin')
            ->sum('nominal');
    }

    public function total_pengeluaran_edupay()
    {
        return $this->total_penarikan_edupay() + $this->total_pembayaran_edupay() + $this->total_pembayaran_kantin();
    }

    public function saldo_edupay_siswa()
    {
        return $this->total_pemasukan_edupay() - $this->total_pengeluaran_edupay();
    }

    // ===============END EDUPAY

    // ===============EDUCARD

    public function ms_educard()
    {
        return $this->hasOne(EduCard::class, 'ms_siswa_id', 'ms_siswa_id')
            ->where('jenis_pemilik', 'siswa');
    }
    // ===============END EDUCARD

    // =============== SMARTCANTEEN ===============
    public function ms_transaksi_smartcanteen()
    {
        return $this->hasMany(TransaksiSmartCanteen::class, 'user_id', 'ms_siswa_id')
            ->where('user_type', 'siswa');
    }

    public function total_transaksi_smartcanteen()
    {
        return $this->ms_transaksi_smartcanteen()->sum('total_transaksi');
    }
    // =============== END SMARTCANTEEN ===============
}
