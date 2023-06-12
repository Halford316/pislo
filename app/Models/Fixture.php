<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'campo_id',
        'equipo_1',
        'equipo_2',
        'arbitro_id',
        'fecha_nro',
        'partido_fecha',
        'partido_hora',
        'equipo_1_goles',
        'equipo_2_goles',
        'user_id',
        'status',
        'partido_nro',
        'juez_linea_1',
        'juez_linea_2'
    ];

    public function torneos()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function campos()
    {
        return $this->belongsTo(Campo::class, 'campo_id');
    }

    public function locales()
    {
        return $this->belongsTo(Equipo::class, 'equipo_1');
    }

    public function visitantes()
    {
        return $this->belongsTo(Equipo::class, 'equipo_2');
    }

    public function arbitros()
    {
        return $this->belongsTo(Arbitro::class, 'arbitro_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
