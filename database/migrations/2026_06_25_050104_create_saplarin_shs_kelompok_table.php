<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_shs_kelompok', function (Blueprint $table) {

            $table->id('kelompok_id');

            $table->uuid('kelompok_uid')->unique();

            $table->string('kelompok_kode',100)->unique();

            $table->string('kelompok_nama',255);

            $table->string('kelompok_tipe',100);

            $table->boolean('kelompok_status')->default(true);

            $table->string('created_by')->nullable();

            $table->string('updated_by')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_shs_kelompok');
    }
};