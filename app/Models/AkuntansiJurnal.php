<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AkuntansiJurnal extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'akuntansi_jurnal';

    protected $primaryKey = 'akuntansi_jurnal_id';

    protected $fillable = [
        'nomor_jurnal',
        'tanggal_transaksi',
        'deskripsi',
        'ms_pengguna_id',
        'ms_tahun_ajaran_id',
        'ms_jenjang_id',
        'ms_departemen_id',
        'status',
    ];
    public static function generateNomorJurnal($tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : now();

        // Prefix: JV260805-
        $prefix = 'JV' . $tanggal->format('ymd') . '-';

        return DB::transaction(function () use ($prefix) {

            $lastNumber = self::where('nomor_jurnal', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('nomor_jurnal')
                ->value('nomor_jurnal');

            if (!$lastNumber) {
                $urut = 1;
            } else {
                $urut = (int) substr($lastNumber, -4) + 1;
            }

            return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
        });
    }
    /**
     * Relasi ke model Pengguna
     */
    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }

    public function akuntansi_jurnal_detail()
    {
        return $this->hasMany(
            AkuntansiJurnalDetail::class,
            'akuntansi_jurnal_id',
            'akuntansi_jurnal_id'
        );
    }
}
