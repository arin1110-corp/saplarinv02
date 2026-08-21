<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_standar_harga_penggunaan', function (Blueprint $table) {

            $table->bigIncrements('penggunaan_id');

            $table->uuid('penggunaan_uid')->unique();

            /*
            |--------------------------------------------------------------------------
            | MASTER STANDAR HARGA
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('penggunaan_standar_harga');

            /*
            |--------------------------------------------------------------------------
            | TAHUN
            |--------------------------------------------------------------------------
            */

            $table->year('penggunaan_tahun');

            /*
            |--------------------------------------------------------------------------
            | OPERATOR
            |--------------------------------------------------------------------------
            */

            $table->string('penggunaan_input_nip', 50)
                ->nullable();

            $table->string('penggunaan_input_nama', 150)
                ->nullable();

            $table->string('penggunaan_unit', 200)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('penggunaan_status')
                ->default(true);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign(
                'penggunaan_standar_harga',
                'sh_penggunaan_master_fk'
            )
                ->references('standar_harga_id')
                ->on('saplarin_standar_harga')
                ->onDelete('restrict');

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['penggunaan_tahun', 'penggunaan_status'],
                'sh_penggunaan_tahun_status_idx'
            );

            $table->index(
                'penggunaan_input_nip',
                'sh_penggunaan_nip_idx'
            );

            $table->index(
                'penggunaan_unit',
                'sh_penggunaan_unit_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_standar_harga_penggunaan');
    }
};