<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneoCampo extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'campo_id',
        'user_id'
    ];

    public function torneos()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function campos()
    {
        return $this->belongsTo(Campo::class, 'campo_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
