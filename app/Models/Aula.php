<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Formata;
use Carbon\Carbon;

class Aula extends Model
{
    protected $fillable = [
        'pacote_id',
        'dia',
        'horario',
    ];

    protected $casts = [
        'pacote_id' => 'integer',
        'dia' => 'date:Y-m-d',
        'horario' => 'datetime:H:i',
    ];

    protected $appends = [
        'dia_formatado',
        'dia_da_semana',
        'dia_da_semana_index',
    ];

    function getDiaFormatadoAttribute()
    {
        return Formata::data($this->dia);
    }

    function getDiadaSemanaAttribute()
    {
        return Carbon::parse($this->dia)->locale('pt_BR')->translatedFormat('l');
    }

    function getDiaDaSemanaIndexAttribute()
    {
        return Carbon::parse($this->dia)->dayOfWeek;
    }
    
    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}
