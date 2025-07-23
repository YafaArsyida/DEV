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
        Schema::create('ms_jabatan', function (Blueprint $table) {
            $table->id('ms_jabatan_id');
            $table->string('nama_jabatan', 100);
            $table->text('deskripsi')->nullable(); // Catatan tambahan
            $table->timestamps(); // Menyimpan created_at dan updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_jabatan');
    }
};
