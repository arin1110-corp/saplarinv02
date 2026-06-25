<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Buat index biasa terlebih dahulu
        DB::statement("
            ALTER TABLE saplarin_sub_kegiatan_laporan
            ADD INDEX idx_laporan_sub_kegiatan (laporan_sub_kegiatan_id)
        ");

        // Hapus unique lama
        DB::statement("
            ALTER TABLE saplarin_sub_kegiatan_laporan
            DROP INDEX uk_skl_sub_bulan_tahun
        ");

        // Buat unique baru
        DB::statement("
            ALTER TABLE saplarin_sub_kegiatan_laporan
            ADD UNIQUE uk_skl_unit_sub_bulan_tahun
            (
                laporan_unit_kode,
                laporan_sub_kegiatan_id,
                laporan_bulan,
                laporan_tahun
            )
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE saplarin_sub_kegiatan_laporan
            DROP INDEX uk_skl_unit_sub_bulan_tahun
        ");

        DB::statement("
            ALTER TABLE saplarin_sub_kegiatan_laporan
            ADD UNIQUE uk_skl_sub_bulan_tahun
            (
                laporan_sub_kegiatan_id,
                laporan_bulan,
                laporan_tahun
            )
        ");

        DB::statement("
            ALTER TABLE saplarin_sub_kegiatan_laporan
            DROP INDEX idx_laporan_sub_kegiatan
        ");
    }
};