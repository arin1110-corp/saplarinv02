<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_shs', function (Blueprint $table) {
            $table->id('shs_id');

            $table->uuid('shs_uid')->unique();

            $table->year('shs_tahun');

            // UNIT
            $table->string('shs_unit_kode', 50);
            $table->string('shs_unit_nama', 200);

            // Barang

            $table->string('shs_kode_kelompok', 100);
            $table->index('shs_kode_kelompok');

            $table->string('shs_kelompok_barang', 255);
            $table->index('shs_kelompok_barang');

            $table->string('shs_barang', 255);

            // Tambahan
            $table->string('shs_merek', 255)->nullable();

            $table->string('shs_tipe', 255)->nullable();

            $table->text('shs_spesifikasi');

            $table->string('shs_satuan', 100);

            $table->decimal('shs_harga', 18, 2);

            $table->decimal('shs_tkdn', 5, 2)->nullable();

            $table->text('shs_link_survei')->nullable();

            $table->text('shs_dasar_usulan')->nullable();

            $table->text('shs_keterangan')->nullable();

            $table->string('shs_kelompok', 50)->default('SSH');

            // status

            $table->string('shs_status', 50);

            $table->text('shs_catatan_admin')->nullable();
            $table->timestamp('shs_verifikasi_at')->nullable();

            $table->string('shs_verifikasi_oleh')->nullable();

            // Operator

            $table->bigInteger('shs_operator_id')->nullable();

            $table->string('shs_operator_nama')->nullable();

            $table->string('shs_operator_nip')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_shs');
    }
};