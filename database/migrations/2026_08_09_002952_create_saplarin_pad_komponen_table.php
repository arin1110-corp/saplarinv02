<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_pad_komponen', function (Blueprint $table) {

            $table->id('pad_komponen_id');

            $table->uuid('pad_komponen_uid')
                ->unique();

            $table->unsignedBigInteger('pad_komponen_jenis');

            $table->string('pad_komponen_kode', 100)
                ->nullable();

            $table->string('pad_komponen_nama');

            $table->text('pad_komponen_keterangan')
                ->nullable();

            $table->boolean('pad_komponen_status')
                ->default(true);

            $table->timestamps();

            $table->foreign('pad_komponen_jenis')
                ->references('pad_jenis_id')
                ->on('saplarin_pad_jenis')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_pad_komponen');
    }
};