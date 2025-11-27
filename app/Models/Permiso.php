<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'licencia_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function licencia()
    {
        return $this->belongsTo(Licencia::class);
    }

    // Scopes
    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->where('fecha_inicio', '<=', $fecha)
                    ->where('fecha_fin', '>=', $fecha);
    }

    // Métodos de utilidad
    public function getDiasDuracionAttribute()
    {
        return $this->fecha_inicio->diffInDays($this->fecha_fin) + 1;
    }

    public function estaActivoEnFecha($fecha)
    {
        return $fecha >= $this->fecha_inicio && $fecha <= $this->fecha_fin;
    }
}