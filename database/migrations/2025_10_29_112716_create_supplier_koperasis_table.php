<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ms_supplier_koperasi', function (Blueprint $table) {
            $table->id('ms_supplier_koperasi_id'); // primary key sesuai model
            $table->string('nama_supplier_koperasi');
            $table->text('alamat')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ms_supplier_koperasi');
    }
};
