<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('saplarin_sub_kegiatan_laporan', function (Blueprint $table) {
            $table->longText('laporan_catatan_admin')->nullable();

            $table->timestamp('laporan_catatan_at')->nullable();

            $table->string('laporan_catatan_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('saplarin_sub_kegiatan_laporan', function (Blueprint $table) {
            $table->dropColumn(['laporan_catatan_admin', 'laporan_catatan_at', 'laporan_catatan_by']);
        });
    }
};