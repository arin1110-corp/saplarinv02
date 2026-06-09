<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_kinerja_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_bidang_id')) {
                $table->unsignedBigInteger('progress_bidang_id')->nullable()->after('progress_user_nip');
            }

            if (!Schema::hasColumn('saplarin_kinerja_progress', 'progress_bidang_nama')) {
                $table->string('progress_bidang_nama')->nullable()->after('progress_bidang_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_kinerja_progress', function (Blueprint $table) {
            $table->dropColumn(['progress_bidang_id', 'progress_bidang_nama']);
        });
    }
};