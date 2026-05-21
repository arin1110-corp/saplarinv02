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
        Schema::create('saplarin_kegiatan', function (Blueprint $table) {
            $table->id('kegiatan_id');

            $table->string('kegiatan_uid')->unique();

            // relasi ke program
            $table->unsignedBigInteger('kegiatan_program');

            $table->string('kegiatan_nama');

            $table->boolean('kegiatan_status')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_kegiatan');
    }
};