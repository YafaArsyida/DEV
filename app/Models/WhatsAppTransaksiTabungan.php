<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTransaksiTabungan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_whatsapp_transaksi_tabungan'; // Nama tabel
    protected $primaryKey = 'ms_whatsapp_transaksi_tabungan_id'; // Nama kolom primary key

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'ms_jenjang_id',
        'judul',
        'salam_pembuka',
        'kalimat_pembuka',
        'kalimat_penutup',
        'salam_penutup',
    ];
}
