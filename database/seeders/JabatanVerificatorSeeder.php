<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ModelJabatanVerificator;
use Illuminate\Database\Seeder;

class JabatanVerificatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelJabatanVerificator::truncate();
        ModelJabatanVerificator::create(
        [
            'jabatanverificator_nama' => 'Verificator',
            'jabatanverificator_status' => 1,
        ]);
        ModelJabatanVerificator::create(
        [
            'jabatanverificator_nama' => 'Verificator Ahli Pertama',
            'jabatanverificator_status' => 1,
        ]);
        ModelJabatanVerificator::create(
        [
            'jabatanverificator_nama' => 'Verificator Ahli Muda',
            'jabatanverificator_status' => 1,
        ]);
    }
}