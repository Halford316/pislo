<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expulsion extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'campo_id',
        'fixture_id',
        'jugador_id'
    ];

    public function torneos()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function campos()
    {
        return $this->belongsTo(Campo::class, 'campo_id');
    }

    public function fixtures()
    {
        return $this->belongsTo(Fixture::class, 'fixture_id');
    }

    public function jugadores()
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

}
