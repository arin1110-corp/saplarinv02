<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ModelVerificator extends Model
{
    use HasApiTokens, HasFactory;

    // Tentukan tabel yang digunakan oleh model
    protected $table = 'saplarin_verificator';
    // Tentukan primary key jika tidak menggunakan 'id'
    protected $primaryKey = 'verificator_id';
    // Menonaktifkan timestamps jika kolom created_at dan updated_at tidak ada di tabel
    public $timestamps = false;

    protected $fillable = [
        'verificator_nama',
        'verificator_notelp',
        'verificator_jk',
        'verificator_golongan',
        'verificator_jabatan',
        'verificator_foto',
        'verificator_role',
        'verificator_email',
        'verificator_nip',
        'verificator_password',
        'verificator_status',
    ];
    // Cek apakah login menggunakan NIP atau email
    public function findForPassport($identifier)
    {
        return $this->where('verifikator_nip', $identifier)
            ->orWhere('verifikator_email', $identifier)
            ->first();
    }
}