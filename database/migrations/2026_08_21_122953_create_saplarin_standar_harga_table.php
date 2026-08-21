<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_standar_harga', function (Blueprint $table) {

            $table->bigIncrements('standar_harga_id');

            $table->uuid('standar_harga_uid')->unique();

            /*
            |--------------------------------------------------------------------------
            | JENIS
            |--------------------------------------------------------------------------
            | ASB / SSH
            |--------------------------------------------------------------------------
            */

            $table->enum('standar_harga_jenis', [
                'ASB',
                'SSH',
            ]);

            /*
            |--------------------------------------------------------------------------
            | TAHUN
            |--------------------------------------------------------------------------
            */

            $table->year('standar_harga_tahun');

            /*
            |--------------------------------------------------------------------------
            | DATA BARANG
            |--------------------------------------------------------------------------
            */

            $table->string('standar_harga_kode_kelompok', 100)->nullable();

            $table->text('standar_harga_uraian_kelompok')->nullable();

            $table->string('standar_harga_id_standar', 100)->nullable();

            $table->string('standar_harga_kode_barang', 100)->nullable();

            $table->text('standar_harga_uraian_barang')->nullable();

            $table->text('standar_harga_spesifikasi')->nullable();

            $table->string('standar_harga_satuan', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | HARGA
            |--------------------------------------------------------------------------
            */

            $table->decimal('standar_harga_satuan_harga', 20, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | REKENING
            |--------------------------------------------------------------------------
            */

            $table->string('standar_harga_kode_rekening', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('standar_harga_status')
                ->default(true);

            /*
            |--------------------------------------------------------------------------
            | AUDIT
            |--------------------------------------------------------------------------
            */

            $table->string('standar_harga_input_nip', 50)->nullable();

            $table->string('standar_harga_input_nama', 150)->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['standar_harga_tahun', 'standar_harga_jenis'],
                'sh_tahun_jenis_idx'
            );

            $table->index(
                'standar_harga_kode_barang',
                'sh_kode_barang_idx'
            );

            $table->index(
                'standar_harga_kode_kelompok',
                'sh_kode_kelompok_idx'
            );

            $table->index(
                'standar_harga_kode_rekening',
                'sh_kode_rekening_idx'
            );

            $table->index(
                'standar_harga_status',
                'sh_status_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_standar_harga');
    }
};