<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierKoperasi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ms_supplier_koperasi';
    protected $primaryKey = 'ms_supplier_koperasi_id';
    protected $fillable = [
        'nama_supplier_koperasi',
        'alamat',
        'telepon',
        'email',
        'npwp',
        'deskripsi',
    ];
}
