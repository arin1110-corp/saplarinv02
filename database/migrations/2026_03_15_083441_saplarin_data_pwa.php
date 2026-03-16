<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('saplarin_data_pwa', function (Blueprint $table) {
            $table->increments('data_pwa_id');
            $table->integer('data_pwa_subkegiatan');
            $table->year('data_pwa_tahun');
            $table->string('data_pwa_pagu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('saplarin_data_pwa');
    }
};