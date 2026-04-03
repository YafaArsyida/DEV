<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoTabungan extends Model
{
    use HasFactory;

    protected $table = 'ms_saldo_tabungan';
    protected $primaryKey = 'ms_saldo_tabungan_id';

    protected $fillable = [
        'user_id',
        'user_type',
        'saldo_tabungan',
    ];

    /**
     * Scope untuk siswa
     */
    public function scopeSiswa($query)
    {
        return $query->where('user_type', 'siswa');
    }

    /**
     * Scope untuk pegawai
     */
    public function scopePegawai($query)
    {
        return $query->where('user_type', 'pegawai');
    }

    /**
     * Helper ambil saldo (auto create jika belum ada)
     */
    public static function getSaldo($userId, $userType)
    {
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'user_type' => $userType,
            ],
            [
                'saldo_tabungan' => 0
            ]
        );
    }

}
