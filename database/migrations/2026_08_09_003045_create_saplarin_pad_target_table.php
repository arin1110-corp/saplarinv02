<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_pad_target', function (Blueprint $table) {
            $table->id('pad_target_id');

            $table->uuid('pad_target_uid')->unique();

            /*
            |--------------------------------------------------------------------------
            | TAHUN
            |--------------------------------------------------------------------------
            */

            $table->year('pad_target_tahun');

            /*
            |--------------------------------------------------------------------------
            | JENIS PAD
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('pad_target_jenis');

            /*
            |--------------------------------------------------------------------------
            | KOMPONEN PAD
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('pad_target_komponen')->nullable();

            /*
            |--------------------------------------------------------------------------
            | UNIT
            |--------------------------------------------------------------------------
            */

            $table->string('pad_target_unit');

            $table->string('pad_target_unit_kode')->nullable();

            $table->string('pad_target_unit_nama');

            /*
            |--------------------------------------------------------------------------
            | NOMINAL
            |--------------------------------------------------------------------------
            */

            $table->decimal('pad_target_nominal', 18, 2)->default(0);

            $table->decimal('pad_target_rencana', 18, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | KETERANGAN
            |--------------------------------------------------------------------------
            */

            $table->text('pad_target_keterangan')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('pad_target_status')->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('pad_target_jenis')->references('pad_jenis_id')->on('saplarin_pad_jenis');

            $table->foreign('pad_target_komponen')->references('pad_komponen_id')->on('saplarin_pad_komponen')->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE
            |--------------------------------------------------------------------------
            */

            $table->unique(['pad_target_tahun', 'pad_target_komponen', 'pad_target_unit'], 'pad_target_tahun_komponen_unit_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_pad_target');
    }
};