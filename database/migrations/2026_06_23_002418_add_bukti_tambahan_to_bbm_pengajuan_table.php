<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {
            $table->longText('bbm_bukti_tambahan_file')->nullable();

            $table->boolean('bbm_bukti_tambahan_sync')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saplarin_bbm_pengajuan', function (Blueprint $table) {
            //
            $table->dropColumn([
                'bbm_bukti_tambahan_file',
                'bbm_bukti_tambahan_sync',
            ]);
        });
    }
};