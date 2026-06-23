<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_spj_unit', function (Blueprint $table) {
            $table->id('unit_id');
            $table->uuid('unit_uid')->unique();
            $table->string('unit_kode', 50)->unique();
            $table->string('unit_nama');
            $table->boolean('unit_status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_spj_unit');
    }
};