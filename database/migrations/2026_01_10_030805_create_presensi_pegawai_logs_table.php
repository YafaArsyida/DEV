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
        Schema::create('presensi_pegawai_log', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('cloud_id')->nullable();
            $table->string('pin');
            $table->dateTime('scan_time');
            $table->integer('verify_method')->nullable();
            $table->integer('status_scan')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_pegawai_log');
    }
};
