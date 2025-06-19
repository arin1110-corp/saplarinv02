<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelVerificator;
use Illuminate\Support\Facades\Hash;

class VerificatorSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data dummy ke dalam tabel saplarin_verificator
        
      // Kosongkan tabel users dan reset auto-increment ID
        ModelVerificator::truncate();
        ModelVerificator::create([
            'verificator_nama' => 'John Doe',
            'verificator_notelp' => '08123456789',
            'verificator_jk' => 'L',
            'verificator_golongan' => '7',
            'verificator_jabatan' => '1',
            'verificator_foto' => 'john_doe.jpg',
            'verificator_email' => 'indraardika@gmail.com',
            'verificator_nip' => '199510112020121001',
            'verificator_password' => Hash::make('password123'), // Pastikan password di-hash
            'verificator_status' => '1',
        ]);
    }
}