<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_json', function (Blueprint $table) {
            $table->id('json_id');
            $table->string('json_nama');
            $table->string('json_file');
            $table->tinyInteger('json_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_json');
    }
};