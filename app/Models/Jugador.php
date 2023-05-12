<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipo_id',
        'ape_paterno',
        'ape_materno',
        'nombres',
        'fecha_nac',
        'sexo',
        'foto',
        'telefono',
        'status'
    ];

    public function equipos()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }


}
