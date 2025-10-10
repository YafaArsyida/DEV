<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuitansiTransaksiTabungan extends Model
{
    use HasFactory;
    protected $table = 'ms_kuitansi_transaksi_tabungan'; // Nama tabel
    protected $primaryKey = 'ms_kuitansi_transaksi_tabungan_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_jenjang_id',
        'logo',          // Logo
        'nama_institusi',    // nama sekolah
        'alamat',       // alamat
        'kontak',          // kontak
        'judul',               // judul
        'pesan',               // pesan
        'tempat',               // tempat
    ];
}
