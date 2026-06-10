<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sadarin_program_prioritas_capaian', function (Blueprint $table) {
            if (!Schema::hasColumn('sadarin_program_prioritas_capaian', 'capaian_jumlah')) {
                $table->integer('capaian_jumlah')->default(1)->after('capaian_deskripsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sadarin_program_prioritas_capaian', function (Blueprint $table) {
            $table->dropColumn('capaian_jumlah');
        });
    }
};