<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_pad_realisasi', function (Blueprint $table) {

            $table->id('pad_realisasi_id');

            $table->uuid('pad_realisasi_uid')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | TARGET
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger(
                'pad_realisasi_target'
            );


            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            $table->date(
                'pad_realisasi_tanggal'
            );


            /*
            |--------------------------------------------------------------------------
            | NOMINAL
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'pad_realisasi_nominal',
                18,
                2
            );


            /*
            |--------------------------------------------------------------------------
            | KETERANGAN
            |--------------------------------------------------------------------------
            */

            $table->text(
                'pad_realisasi_keterangan'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | DOKUMEN ARINDRIVE
            |--------------------------------------------------------------------------
            */

            $table->string(
                'pad_realisasi_dokumen'
            )->nullable();

            $table->string(
                'pad_realisasi_dokumen_nama'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | OPERATOR
            |--------------------------------------------------------------------------
            */

            $table->string(
                'pad_realisasi_input'
            )->nullable();

            $table->string(
                'pad_realisasi_input_nama'
            )->nullable();

            $table->string(
                'pad_realisasi_input_unit'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->string(
                'pad_realisasi_status',
                50
            )->default('Aktif');


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign(
                'pad_realisasi_target'
            )
                ->references('pad_target_id')
                ->on('saplarin_pad_target')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'saplarin_pad_realisasi'
        );
    }
};