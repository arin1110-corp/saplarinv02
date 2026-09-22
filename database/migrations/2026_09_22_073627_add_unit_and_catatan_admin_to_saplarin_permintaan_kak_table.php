<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('saplarin_permintaan_kak', function (Blueprint $table) {
            $table->string('kak_unit', 255)->nullable()->after('kak_sub_kegiatan_id');

            $table->text('kak_catatan_admin')->nullable()->after('kak_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saplarin_permintaan_kak', function (Blueprint $table) {
            $table->dropColumn(['kak_unit', 'kak_catatan_admin']);
        });
    }
};