<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_spj_pagu', function (Blueprint $table) {
            $table->unsignedBigInteger('spj_pagu_unit_id')->nullable()->after('spj_pagu_tahun');

            $table->foreign('spj_pagu_unit_id')
                ->references('unit_id')
                ->on('saplarin_spj_unit')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_spj_pagu', function (Blueprint $table) {
            $table->dropForeign(['spj_pagu_unit_id']);
            $table->dropColumn('spj_pagu_unit_id');
        });
    }
};