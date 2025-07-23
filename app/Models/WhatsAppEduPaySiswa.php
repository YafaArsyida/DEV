<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsAppEduPaySiswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_whatsapp_edupay'; // Nama tabel
    protected $primaryKey = 'ms_whatsapp_edupay_id'; // Nama kolom primary key

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
