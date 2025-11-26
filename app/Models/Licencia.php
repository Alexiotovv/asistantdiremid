<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Scope para licencias activas
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}