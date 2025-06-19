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
        Schema::create('saplarin_admin', function (Blueprint $table) {
            $table->increments('admin_id');
            $table->string('admin_username', 100);
            $table->string('admin_password', 200);
            $table->integer('admin_status');
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