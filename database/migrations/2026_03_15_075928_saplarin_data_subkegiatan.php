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
        Schema::create('saplarin_subkegiatan_pwa', function (Blueprint $table) {
            $table->increments('subkegiatan_pwa_id');
            $table->string('subkegiatan_pwa_nama', 255);
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
        Schema::dropIfExists('saplarin_subkegiatan_pwa');
    }
};