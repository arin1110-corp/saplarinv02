<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {
            $table->string('bbm_acc_pimpinan_file')->nullable()->after('bbm_spt_file');
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {
            $table->dropColumn('bbm_acc_pimpinan_file');
        });
    }
};