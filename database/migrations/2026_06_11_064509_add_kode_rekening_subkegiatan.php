<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    

        Schema::table('saplarin_sub_kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('saplarin_sub_kegiatan', 'sub_kegiatan_kode_rekening')) {
                $table->string('sub_kegiatan_kode_rekening')->nullable()->after('sub_kegiatan_id');
            }
        });
    }

    public function down(): void
    {
        

        Schema::table('saplarin_sub_kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('saplarin_sub_kegiatan', 'sub_kegiatan_kode_rekening')) {
                $table->dropColumn('sub_kegiatan_kode_rekening');
            }
        });
    }
};