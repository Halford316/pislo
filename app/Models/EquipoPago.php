<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipo_id',
        'torneo_id',
        'registracion',
        'adelanto',
        'saldo',
        'status',
        'user_id'
    ];

    public function equipos()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function torneos()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
