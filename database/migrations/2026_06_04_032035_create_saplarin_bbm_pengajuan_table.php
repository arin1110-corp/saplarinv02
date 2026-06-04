<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_bbm_pengajuan', function (Blueprint $table) {
            $table->id('bbm_id');
            $table->uuid('bbm_uid')->unique();

            $table->unsignedBigInteger('bbm_pengaju_id');
            $table->string('bbm_pengaju_nama')->nullable();
            $table->string('bbm_pengaju_nip')->nullable();
            $table->string('bbm_pengaju_email')->nullable();
            $table->string('bbm_bidang_nama')->nullable();

            $table->unsignedBigInteger('bbm_sub_kegiatan_id');

            $table->text('bbm_uraian_kegiatan');
            $table->string('bbm_spt_file')->nullable();

            $table->enum('bbm_status_pengajuan', [
                'Menunggu Verifikasi',
                'Pengajuan Diterima',
                'Pengajuan Ditolak',
            ])->default('Menunggu Verifikasi');

            $table->date('bbm_tanggal_nota')->nullable();
            $table->string('bbm_laporan_nota_file')->nullable();

            $table->enum('bbm_status_laporan', [
                'Belum Upload',
                'Menunggu Verifikasi',
                'Laporan Nota Diterima',
                'Laporan Nota Ditolak',
            ])->default('Belum Upload');

            $table->text('bbm_catatan_admin')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_bbm_pengajuan');
    }
};