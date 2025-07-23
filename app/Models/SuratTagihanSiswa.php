<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTagihanSiswa extends Model
{
    use HasFactory;
    protected $table = 'ms_surat_tagihan_siswa'; // Nama tabel
    protected $primaryKey = 'ms_surat_tagihan_siswa_id'; // Nama kolom primary key

    protected $fillable = [
        'ms_jenjang_id',
        'foto_kop',          // Foto KOP Surat
        'tempat_tanggal',    // Tempat dan Tanggal
        'nomor_surat',       // Nomor Surat
        'lampiran',          // Lampiran
        'hal',               // Hal
        'salam_pembuka',     // Salam Pembuka
        'pembuka',           // Paragraf Pembuka
        'isi',               // Isi Surat
        'rincian',           // Rincian Tagihan
        'panduan',           // Panduan Pembayaran
        'instruksi_1',       // Instruksi 1
        'instruksi_2',       // Instruksi 2
        'instruksi_3',       // Instruksi 3
        'instruksi_4',       // Instruksi 4
        'instruksi_5',       // Instruksi 5
        'penutup',           // Penutup
        'salam_penutup',     // Salam Penutup
        'jabatan',           // Jabatan Penandatangan
        'tanda_tangan',      // Tanda Tangan Digital
        'nama_petugas',      // Nama Petugas
        'nomor_petugas',     // Nomor Telepon Petugas
        'catatan_1',         // Catatan Tambahan 1
        'catatan_2',         // Catatan Tambahan 2
        'catatan_3',         // Catatan Tambahan 3
    ];
}
