<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marcacion extends Model
{
    protected $fillable = ['user_id', 'fecha', 'hora', 'tipo'];
     protected $table = 'marcaciones';
    protected $casts = [
        'fecha' => 'date',
        'hora' => 'string',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}