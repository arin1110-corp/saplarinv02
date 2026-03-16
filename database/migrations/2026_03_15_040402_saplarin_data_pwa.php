<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('saplarin_laporan_pwa', function (Blueprint $table) {
            $table->increments('laporan_pwa_id');
            $table->string('laporan_pwa_subkegiatan', 100);
            $table->text('laporan_pwa_keterangan')->nullable();
            $table->bigInteger('laporan_pwa_nominal');
            $table->string('laporan_pwa_file', 255)->nullable(); // link file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_laporan_pwa');
    }
};