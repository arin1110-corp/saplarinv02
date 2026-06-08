<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_progress_kinerja', function (Blueprint $table) {
            $table->id('progress_id');
            $table->uuid('progress_uid')->unique();

            $table->unsignedBigInteger('progress_bidang_id')->nullable();
            $table->string('progress_bidang_nama');

            $table->string('progress_kegiatan');
            $table->text('progress_deskripsi')->nullable();

            $table->date('progress_tanggal_mulai')->nullable();
            $table->date('progress_tanggal_selesai')->nullable();

            $table->decimal('progress_persentase', 5, 2)->default(0);

            $table->date('progress_user_tanggal_mulai')->nullable();
            $table->date('progress_user_tanggal_selesai')->nullable();
            $table->decimal('progress_user_persentase', 5, 2)->nullable();

            $table->string('progress_status')->default('Belum Diisi');

            $table->unsignedBigInteger('progress_created_by')->nullable();
            $table->string('progress_created_by_nama')->nullable();

            $table->unsignedBigInteger('progress_updated_by')->nullable();
            $table->string('progress_updated_by_nama')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_progress_kinerja');
    }
};