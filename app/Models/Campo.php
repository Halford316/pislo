<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_campo',
        'descripcion',
        'status',
        'activo',
        'user_id'
    ];

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
