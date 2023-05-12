<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'foto',
        'activo',
        'user_id'
    ];

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
