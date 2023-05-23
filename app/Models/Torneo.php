<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'premio',
        'nro_equipos',
        'direccion',
        'lugar',
        'duracion',
        'descanso',
        'hora_inicio',
        'status',
        'activo',
        'user_id',
        'precio',
        'fixture'
    ];

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
