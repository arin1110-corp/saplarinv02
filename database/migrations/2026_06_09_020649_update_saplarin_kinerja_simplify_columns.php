<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_kinerja', function (Blueprint $table) {
            if (Schema::hasColumn('saplarin_kinerja', 'kinerja_target_persen')) {
                $table->dropColumn('kinerja_target_persen');
            }

            if (Schema::hasColumn('saplarin_kinerja', 'kinerja_tanggal_mulai')) {
                $table->dropColumn('kinerja_tanggal_mulai');
            }

            if (Schema::hasColumn('saplarin_kinerja', 'kinerja_tanggal_selesai')) {
                $table->dropColumn('kinerja_tanggal_selesai');
            }

            if (Schema::hasColumn('saplarin_kinerja', 'kinerja_pic_id')) {
                $table->dropColumn('kinerja_pic_id');
            }

            if (Schema::hasColumn('saplarin_kinerja', 'kinerja_pic_nama')) {
                $table->dropColumn('kinerja_pic_nama');
            }

            if (Schema::hasColumn('saplarin_kinerja', 'kinerja_pic_nip')) {
                $table->dropColumn('kinerja_pic_nip');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_kinerja', function (Blueprint $table) {
            $table->decimal('kinerja_target_persen', 5, 2)->default(100)->after('kinerja_deskripsi');
            $table->date('kinerja_tanggal_mulai')->nullable()->after('kinerja_target_persen');
            $table->date('kinerja_tanggal_selesai')->nullable()->after('kinerja_tanggal_mulai');
            $table->unsignedBigInteger('kinerja_pic_id')->nullable()->after('kinerja_tanggal_selesai');
            $table->string('kinerja_pic_nama')->nullable()->after('kinerja_pic_id');
            $table->string('kinerja_pic_nip')->nullable()->after('kinerja_pic_nama');
        });
    }
};