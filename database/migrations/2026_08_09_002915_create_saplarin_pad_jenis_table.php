<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_pad_jenis', function (Blueprint $table) {

            $table->id('pad_jenis_id');

            $table->uuid('pad_jenis_uid')
                ->unique();

            $table->string('pad_jenis_kode', 100)
                ->nullable();

            $table->string('pad_jenis_nama');

            $table->text('pad_jenis_keterangan')
                ->nullable();

            $table->boolean('pad_jenis_status')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_pad_jenis');
    }
};