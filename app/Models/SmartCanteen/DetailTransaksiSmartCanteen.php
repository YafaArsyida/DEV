<?php

namespace App\Models\SmartCanteen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksiSmartCanteen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dt_transaksi_kantin'; // Nama tabel
    protected $primaryKey = 'dt_transaksi_kantin_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_transaksi_kantin_id',
        'ms_produk_kantin_id',
        'jumlah_produk',
        'jumlah_bayar',
        'deskripsi',

        'status_transaksi'
    ];

    public function ms_transaksi_kantin()
    {
        return $this->belongsTo(TransaksiSmartCanteen::class, 'ms_transaksi_kantin_id');
    }

    public function ms_produk_kantin()
    {
        return $this->belongsTo(ProdukSmartCanteen::class, 'ms_produk_kantin_id');
    }
}
