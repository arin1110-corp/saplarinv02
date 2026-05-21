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
        Schema::create('saplarin_program', function (Blueprint $table) {

            $table->id('program_id');

            // id user SADARIN
            $table->string('program_uid',150);

            $table->string('program_nama');
            $table->boolean('program_status');

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
        Schema::dropIfExists('saplarin_program');
    }
};