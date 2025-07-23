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
        Schema::create('ms_jenjang', function (Blueprint $table) {
            $table->id('ms_jenjang_id');
            $table->string('nama_jenjang', 100);
            $table->integer('urutan');
            $table->enum('status', ['aktif', 'nonaktif']);
            $table->text('deskripsi')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenjangs');
    }
};
