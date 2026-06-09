<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_kinerja', function (Blueprint $table) {
            $table->id('kinerja_id');
            $table->uuid('kinerja_uid')->unique();

            $table->year('kinerja_tahun');

            $table->unsignedBigInteger('kinerja_bidang_id')->nullable();
            $table->string('kinerja_bidang_nama');

            $table->string('kinerja_kegiatan');
            $table->text('kinerja_deskripsi')->nullable();

            $table->decimal('kinerja_target_persen', 5, 2)->default(100);

            $table->date('kinerja_tanggal_mulai');
            $table->date('kinerja_tanggal_selesai');

            $table->unsignedBigInteger('kinerja_pic_id')->nullable();
            $table->string('kinerja_pic_nama')->nullable();
            $table->string('kinerja_pic_nip')->nullable();

            $table->string('kinerja_status')->default('Aktif');

            $table->unsignedBigInteger('kinerja_created_by')->nullable();
            $table->string('kinerja_created_by_nama')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_kinerja');
    }
};