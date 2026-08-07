<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_ruang', function (Blueprint $table) {

            $table->bigIncrements('ruang_id');

            $table->uuid('ruang_uid')->unique();

            $table->string('ruang_nama',100);

            $table->string('ruang_lokasi',150)->nullable();

            $table->unsignedInteger('ruang_kapasitas')->default(0);

            $table->text('ruang_keterangan')->nullable();

            $table->tinyInteger('ruang_status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_ruang');
    }
};