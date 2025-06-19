<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelJabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelJabatan::truncate();
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Kepala Dinas',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Sekretaris Dinas',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Kepala Bidang',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Kepala Seksi',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Kepala Sub Bagian',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Staff Administrasi',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Analis',
            'jabatan_status' => 1,
        ]);
        ModelJabatan::create(
        [
            'jabatan_nama' => 'Pranata Komputer Ahli Pertama',
            'jabatan_status' => 1,
        ]);
        
    }
}