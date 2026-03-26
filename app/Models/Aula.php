<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $fillable = [
        'turma_id',
        'horario',
        // 'duracao', // Esse item talvez pertença ao núcleo, já que é lá que definimos o público alvo
        'data',
    ];

    protected $casts = [
        'turma_id' => 'integer',
        'horario_inicio' => 'time',
        'horario_fim' => 'time',
        'data' => 'date'
    ];

    protected $appends = [
        'dia',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
}
