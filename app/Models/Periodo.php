<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
    protected $fillable = [
        'inicio',
        'fim',
        'pacote_id',
    ];

    function getInicioAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }

    function getFimAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}
