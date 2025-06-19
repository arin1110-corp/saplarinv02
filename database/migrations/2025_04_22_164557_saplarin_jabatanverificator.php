<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('saplarin_jabatanverificator', function (Blueprint $table) {
            $table->increments('jabatanverificator_id');
            $table->string('jabatanverificator_nama', 100);
            $table->integer('jabatanverificator_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};