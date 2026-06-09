<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_laporan_kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_laporan_kegiatan', 'laporan_kegiatan_sub_kegiatan_id')) {
                $table->unsignedBigInteger('laporan_kegiatan_sub_kegiatan_id')->nullable()->after('laporan_kegiatan_nama');
            }

            if (!Schema::hasColumn('saplarin_laporan_kegiatan', 'laporan_kegiatan_sub_kegiatan_nama')) {
                $table->string('laporan_kegiatan_sub_kegiatan_nama')->nullable()->after('laporan_kegiatan_sub_kegiatan_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_laporan_kegiatan', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_kegiatan_sub_kegiatan_id',
                'laporan_kegiatan_sub_kegiatan_nama',
            ]);
        });
    }
};