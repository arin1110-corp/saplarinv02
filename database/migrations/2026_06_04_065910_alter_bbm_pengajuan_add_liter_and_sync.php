<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {

            $table->decimal('bbm_liter', 10, 2)
                ->after('bbm_uraian_kegiatan');

            $table->boolean('bbm_spt_sync')
                ->default(false)
                ->after('bbm_spt_file');

            $table->boolean('bbm_acc_pimpinan_sync')
                ->default(false)
                ->after('bbm_acc_pimpinan_file');

            $table->boolean('bbm_laporan_nota_sync')
                ->default(false)
                ->after('bbm_laporan_nota_file');
        });
    }

    public function down(): void
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {

            $table->dropColumn([
                'bbm_liter',

                'bbm_spt_sync',
                'bbm_acc_pimpinan_sync',
                'bbm_laporan_nota_sync',
            ]);
        });
    }
};