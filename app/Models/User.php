<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    const ROLE_ADMIN = 'admin';
    const ROLE_PERSONAL = 'personal';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dni',
        'nombre_completo',
        'oficina',
        'clave_pin',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function marcaciones()
    {
        return $this->hasMany(Marcacion::class);
    }

    public function getRoleDisplayAttribute()
    {
        return $this->role === 'admin' ? 'Administrador' : 'Personal';
    }
}