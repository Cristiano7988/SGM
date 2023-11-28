<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedidaDeTempo extends Model
{
    use HasFactory;
    protected $table = 'medida_de_tempo';
    protected $fillable = [
        'tipo'
    ];

    public function idade_minima()
    {
        return $this->hasMany(IdadeMinima::class);
    }

    public function idade_maxima()
    {
        return $this->hasMany(IdadeMaxima::class);
    }
}
