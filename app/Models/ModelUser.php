<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ModelUser extends Model
{
    use HasApiTokens, HasFactory;

    // Tentukan tabel yang digunakan oleh model
    protected $table = 'saplarin_user';
    // Tentukan primary key jika tidak menggunakan 'id'
    protected $primaryKey = 'user_id';
    // Menonaktifkan timestamps jika kolom created_at dan updated_at tidak ada di tabel
    public $timestamps = false;

    protected $fillable = [
        'user_nama',
        'user_notelp',
        'user_jk',
        'user_golongan',
        'user_jabatan',
        'user_foto',
        'user_role',
        'user_email',
        'user_nip',
        'user_password',
        'user_bidang',
        'user_status',
    ];
    // Cek apakah login menggunakan NIP atau email
    public function findForPassport($identifier)
    {
        return $this->where('user_nip', $identifier)
            ->orWhere('user_email', $identifier)
            ->first();
    }

     
}