<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelInstansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelInstansi::create(
            [
                'instansi_nama' => 'Dinas Kebudayaan Provinsi Bali',
                'instansi_status' => 1,
            ]
            );
    }
}