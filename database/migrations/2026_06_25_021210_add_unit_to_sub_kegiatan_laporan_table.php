<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('saplarin_sub_kegiatan_laporan', function (Blueprint $table) {

            $table->string('laporan_unit_kode')
                ->nullable()
                ->after('laporan_uid');

            $table->string('laporan_unit_nama')
                ->nullable()
                ->after('laporan_unit_kode');
        });
    }

    public function down()
    {
        Schema::table('saplarin_sub_kegiatan_laporan', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_unit_kode',
                'laporan_unit_nama'
            ]);
        });
    }
};