<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_kinerja_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_status')) {
                $table->string('progress_status')->default('Menunggu Verifikasi')->after('progress_keterangan');
            }

            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_catatan_verifikasi')) {
                $table->text('progress_catatan_verifikasi')->nullable()->after('progress_status');
            }

            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_verified_by')) {
                $table->unsignedBigInteger('progress_verified_by')->nullable()->after('progress_catatan_verifikasi');
            }

            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_verified_by_nama')) {
                $table->string('progress_verified_by_nama')->nullable()->after('progress_verified_by');
            }

            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_verified_at')) {
                $table->timestamp('progress_verified_at')->nullable()->after('progress_verified_by_nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_kinerja_progress', function (Blueprint $table) {
            $table->dropColumn([
                'progress_status',
                'progress_catatan_verifikasi',
                'progress_verified_by',
                'progress_verified_by_nama',
                'progress_verified_at',
            ]);
        });
    }
};