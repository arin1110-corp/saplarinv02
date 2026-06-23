<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_spj_realisasi', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_spj_realisasi', 'spj_catatan_admin')) {
                $table->text('spj_catatan_admin')->nullable()->after('spj_status');
            }

            if (!Schema::hasColumn('saplarin_spj_realisasi', 'spj_status_by')) {
                $table->string('spj_status_by')->nullable()->after('spj_catatan_admin');
            }

            if (!Schema::hasColumn('saplarin_spj_realisasi', 'spj_status_by_nama')) {
                $table->string('spj_status_by_nama')->nullable()->after('spj_status_by');
            }

            if (!Schema::hasColumn('saplarin_spj_realisasi', 'spj_status_at')) {
                $table->timestamp('spj_status_at')->nullable()->after('spj_status_by_nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_spj_realisasi', function (Blueprint $table) {
            $table->dropColumn([
                'spj_catatan_admin',
                'spj_status_by',
                'spj_status_by_nama',
                'spj_status_at',
            ]);
        });
    }
};