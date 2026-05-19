<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ModelUser extends Authenticatable
{

    use HasFactory;

    // Tentukan tabel yang digunakan oleh model
    protected $table = 'saplarin_user';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'user_uid',
        'user_role',
    ];
    // Cek apakah login menggunakan NIP atau email

    protected $hidden = [
        'remember_token',
    ];

    // helper role (optional)
    public function getRoleAttribute()
    {
        return $this->user_role;
    }
}