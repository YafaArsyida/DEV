<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AkuntansiRekening extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'akuntansi_rekening'; // Nama tabel
    protected $primaryKey = 'akuntansi_rekening_id'; // Nama kolom primary key

    protected $fillable = [
        'akuntansi_rekening_id',
        'akuntansi_kelompok_rekening_id',
        'kode_rekening',
        'nama_rekening',
        'posisi_normal',
        'deskripsi',
    ];
    /**
     * Relasi ke model KategoriTagihan
     */
    public function akuntansi_kelompok_rekening()
    {
        return $this->belongsTo(AkuntansiKelompokRekening::class, 'akuntansi_kelompok_rekening_id', 'akuntansi_kelompok_rekening_id');
    }

    public function akuntansi_jurnal_detail()
    {
        return $this->hasMany(AkuntansiJurnalDetail::class, 'kode_rekening', 'kode_rekening');
    }

    public function saldo_akhir()
    {
        // Ambil total debit dan kredit dari tabel akuntansi_jurnal_detail
        $totalDebit = AkuntansiJurnalDetail::where('kode_rekening', $this->kode_rekening)
            ->where('is_canceled', false)
            ->sum(DB::raw('CASE WHEN posisi = "debit" THEN nominal ELSE 0 END'));

        $totalKredit = AkuntansiJurnalDetail::where('kode_rekening', $this->kode_rekening)
            ->where('is_canceled', false)
            ->sum(DB::raw('CASE WHEN posisi = "kredit" THEN nominal ELSE 0 END'));

        // Hitung saldo akhir berdasarkan posisi normal
        if ($this->posisi_normal === 'debit') {
            return max(0, $totalDebit - $totalKredit);
        } else {
            return max(0, $totalKredit - $totalDebit);
        }
    }
}
