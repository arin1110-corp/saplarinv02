<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_program', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_program', 'program_kode')) {
                $table->string('program_kode')->nullable()->after('program_id');
            }
        });

        Schema::table('saplarin_kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_kegiatan', 'kegiatan_kode')) {
                $table->string('kegiatan_kode')->nullable()->after('kegiatan_id');
            }
        });

        Schema::table('saplarin_sub_kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_sub_kegiatan', 'sub_kegiatan_kode')) {
                $table->string('sub_kegiatan_kode')->nullable()->after('sub_kegiatan_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_program', function (Blueprint $table) {
            if (Schema::hasColumn('saplarin_program', 'program_kode')) {
                $table->dropColumn('program_kode');
            }
        });

        Schema::table('saplarin_kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('saplarin_kegiatan', 'kegiatan_kode')) {
                $table->dropColumn('kegiatan_kode');
            }
        });

        Schema::table('saplarin_sub_kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('saplarin_sub_kegiatan', 'sub_kegiatan_kode')) {
                $table->dropColumn('sub_kegiatan_kode');
            }
        });
    }
};