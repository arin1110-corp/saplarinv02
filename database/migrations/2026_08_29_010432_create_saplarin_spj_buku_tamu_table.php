<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_spj_buku_tamu', function (Blueprint $table) {
            $table->bigIncrements('buku_tamu_id');

            $table->uuid('buku_tamu_uid')->unique();

            // UID SPJ YANG DILIHAT
            $table->uuid('spj_uid')->index();

            // IDENTITAS PENGUNJUNG
            $table->string('buku_tamu_nama', 150);
            $table->string('buku_tamu_nip', 50)->nullable();
            $table->string('buku_tamu_unit', 200)->nullable();

            // TUJUAN MELIHAT FILE
            $table->string('buku_tamu_tujuan', 255);

            // URL FILE SPJ
            $table->text('buku_tamu_file')->nullable();

            // WAKTU AKSES
            $table->timestamp('buku_tamu_waktu')->useCurrent();

            // INFORMASI TEKNIS
            $table->ipAddress('buku_tamu_ip')->nullable();
            $table->text('buku_tamu_user_agent')->nullable();

            $table->timestamps();

            $table->index(['spj_uid', 'buku_tamu_waktu'], 'spj_buku_tamu_spj_waktu_idx');

            $table->index('buku_tamu_nip', 'spj_buku_tamu_nip_idx');

            $table->index('buku_tamu_unit', 'spj_buku_tamu_unit_idx');

            $table->index('buku_tamu_tujuan', 'spj_buku_tamu_tujuan_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_spj_buku_tamu');
    }
};