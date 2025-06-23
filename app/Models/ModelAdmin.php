<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ModelAdmin extends Authenticatable
{
    //
    use Notifiable;
    use HasFactory, HasApiTokens;
    protected $table = 'saplarin_admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;
    protected $fillable = [
        'admin_username',
        'admin_password',
        'admin_status',
    ];

    // Cek apakah login menggunakan username atau email
    public function getAuthPassword()
    {
        return $this->admin_password;
    }
}
