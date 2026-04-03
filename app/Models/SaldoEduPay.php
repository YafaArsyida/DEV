<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoEduPay extends Model
{
    use HasFactory;

    protected $table = 'ms_saldo_edupay';
    protected $primaryKey = 'ms_saldo_edupay_id';

    protected $fillable = [
        'user_id',
        'user_type',
        'saldo_edupay',
    ];

    // SCOPE SISWA
    public function scopeSiswa($query)
    {
        return $query->where('user_type', 'siswa');
    }

    // SCOPE PEGAWAI
    public function scopePegawai($query)
    {
        return $query->where('user_type', 'pegawai');
    }

    // HELPER AMBIL / BUAT SALDO
    public static function getSaldo($userId, $userType)
    {
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'user_type' => $userType,
            ],
            [
                'saldo_edupay' => 0
            ]
        );
    }
}
