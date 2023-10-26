<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nucleo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'imagem',
        'descricao',
        'idade_minima',
        'idade_maxima',
        'inicio_rematricula',
        'fim_rematricula'
    ];
}
