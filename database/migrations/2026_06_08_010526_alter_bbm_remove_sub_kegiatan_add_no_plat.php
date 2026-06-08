<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {
            if (Schema::hasColumn('saplarin_bbm_pengajuan', 'bbm_sub_kegiatan_id')) {
                $table->dropColumn('bbm_sub_kegiatan_id');
            }

            if (!Schema::hasColumn('saplarin_bbm_pengajuan', 'bbm_no_plat')) {
                $table->string('bbm_no_plat')->nullable()->after('bbm_bidang_nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_bbm_pengajuan', 'bbm_sub_kegiatan_id')) {
                $table->unsignedBigInteger('bbm_sub_kegiatan_id')->nullable()->after('bbm_bidang_nama');
            }

            if (Schema::hasColumn('saplarin_bbm_pengajuan', 'bbm_no_plat')) {
                $table->dropColumn('bbm_no_plat');
            }
        });
    }
};