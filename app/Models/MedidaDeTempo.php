<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedidaDeTempo extends Model
{
    use HasFactory;
    protected $table = 'medidas_de_tempo';
    protected $fillable = [
        'tipo'
    ];

    public function idades_minimas()
    {
        return $this->hasMany(IdadeMinima::class);
    }

    public function idades_maximas()
    {
        return $this->hasMany(IdadeMaxima::class);
    }
}
