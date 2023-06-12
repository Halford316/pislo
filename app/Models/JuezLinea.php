<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuezLinea extends Model
{
    use HasFactory;

    protected $fillable = [
        'ape_paterno',
        'ape_materno',
        'nombres',
        'sexo',
        'foto',
        'telefono',
        'status',
        'activo'
    ];

}
