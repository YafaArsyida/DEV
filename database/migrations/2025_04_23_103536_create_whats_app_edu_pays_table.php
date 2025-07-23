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
        Schema::create('ms_whatsapp_edupay', function (Blueprint $table) {
            $table->id('ms_whatsapp_edupay_id');
            $table->string('judul', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('salam_pembuka', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('kalimat_pembuka')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('detail_pembayaran')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('kalimat_penutup')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('salam_penutup', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_whatsapp_edupay');
    }
};
