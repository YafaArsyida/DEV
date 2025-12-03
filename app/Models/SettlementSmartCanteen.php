<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementSmartCanteen extends Model
{
    use HasFactory;

    protected $table = 'ms_settlement_kantin';
    protected $primaryKey = 'ms_settlement_kantin_id';

    protected $fillable = [
        'tanggal_settlement',
        'total_settlement',
        'metode_pembayaran', // tunai / transfer
        'deskripsi',
        'ms_pengguna_id',
        'ms_pengguna_kantin_id',
        'akun_jurnal_debit_id',
        'akun_jurnal_kredit_id',
    ];

    // Settlement -> hasMany Transaksi
    public function ms_transaksi_kantin()
    {
        return $this->hasMany(TransaksiSmartCanteen::class, 'ms_settlement_kantin_id');
    }

    public function ms_pengguna()
    {
        return $this->belongsTo(User::class, 'ms_pengguna_id', 'ms_pengguna_id');
    }
}
