<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('saplarin_pad_realisasi', function (Blueprint $table) {
            $table->unsignedBigInteger('pad_realisasi_subkomponen')->after('pad_realisasi_target');

            $table->foreign('pad_realisasi_subkomponen')->references('pad_subkomponen_id')->on('saplarin_pad_subkomponen')->restrictOnDelete();

            $table->index('pad_realisasi_subkomponen');
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_pad_realisasi', function (Blueprint $table) {
            $table->dropForeign(['pad_realisasi_subkomponen']);

            $table->dropIndex(['pad_realisasi_subkomponen']);

            $table->dropColumn('pad_realisasi_subkomponen');
        });
    }
};