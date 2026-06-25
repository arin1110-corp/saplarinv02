<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_sub_kegiatan_indikator', function (Blueprint $table) {
            $table->string('indikator_unit_kode')->nullable()->after('indikator_sub_kegiatan_id');
            $table->string('indikator_unit_nama')->nullable()->after('indikator_unit_kode');

            $table->index(
                ['indikator_unit_kode', 'indikator_sub_kegiatan_id'],
                'idx_ski_unit_sub'
            );
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_sub_kegiatan_indikator', function (Blueprint $table) {
            $table->dropIndex('idx_ski_unit_sub');

            $table->dropColumn([
                'indikator_unit_kode',
                'indikator_unit_nama',
            ]);
        });
    }
};