<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $fillable = [
        'turma_id',
        'dia_id',
        'horario',
    ];

    protected $casts = [
        'turma_id' => 'integer',
        'dia_id' => 'integer',
        'horario' => 'datetime:H:i',
    ];

    protected $with = [
        'dia'
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function dia()
    {
        return $this->belongsTo(Dia::class);
    }
}
