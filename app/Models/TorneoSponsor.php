<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneoSponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'sponsor_id',
        'user_id'
    ];

    public function torneos()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }

    public function sponsors()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
