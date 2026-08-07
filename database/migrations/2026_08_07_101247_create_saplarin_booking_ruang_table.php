<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_booking_ruang', function (Blueprint $table) {

            $table->bigIncrements('booking_id');

            $table->uuid('booking_uid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Ruang
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('booking_ruang_id');

            /*
            |--------------------------------------------------------------------------
            | Jadwal
            |--------------------------------------------------------------------------
            */

            $table->date('booking_tanggal');

            $table->time('booking_jam_mulai');

            $table->time('booking_jam_selesai');

            /*
            |--------------------------------------------------------------------------
            | Pengajuan
            |--------------------------------------------------------------------------
            */

            $table->text('booking_peruntukan');

            $table->string('booking_surat')->nullable();

            $table->text('booking_catatan')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('booking_status',[
                'Menunggu',
                'Disetujui',
                'Ditolak',
                'Selesai',
                'Batal',
            ])->default('Menunggu');

            /*
            |--------------------------------------------------------------------------
            | Snapshot Pegawai
            |--------------------------------------------------------------------------
            */

            $table->string('booking_created_by');

            $table->string('booking_created_by_nama');

            $table->string('booking_created_by_nip')->nullable();

            $table->string('booking_created_by_unit')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Approval
            |--------------------------------------------------------------------------
            */

            $table->string('booking_verifikator')->nullable();

            $table->timestamp('booking_verifikasi_at')->nullable();

            $table->text('booking_catatan_admin')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->foreign('booking_ruang_id')
                ->references('ruang_id')
                ->on('saplarin_ruang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index([
                'booking_ruang_id',
                'booking_tanggal'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_booking_ruang');
    }
};