<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_kinerja_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->uuid('progress_uid')->unique();

            $table->unsignedBigInteger('progress_kinerja_id');

            $table->date('progress_tanggal_mulai');
            $table->date('progress_tanggal_selesai');

            $table->string('progress_triwulan');
            $table->decimal('progress_persentase', 5, 2);

            $table->text('progress_keterangan')->nullable();

            $table->unsignedBigInteger('progress_user_id')->nullable();
            $table->string('progress_user_nama')->nullable();
            $table->string('progress_user_nip')->nullable();

            $table->timestamps();

            $table->foreign('progress_kinerja_id')
                ->references('kinerja_id')
                ->on('saplarin_kinerja')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_kinerja_progress');
    }
};