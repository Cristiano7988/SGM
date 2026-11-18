<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Formata;
use Carbon\Carbon;

class Aula extends Model
{
    protected $fillable = [
        'pacote_id',
        'endereco_id',
        'turma_id',
        'dia',
        'horario',
        'valor',
        'taxa_de_deslocamento',
        'tempo_de_deslocamento',
        'distancia'
    ];

    protected $casts = [
        'pacote_id' => 'integer',
        'endereco_id' => 'integer',
        'dia' => 'date:Y-m-d',
        'horario' => 'datetime:H:i',
        'valor' => 'float',
        'taxa_de_deslocamento' => 'float',
        'tempo_de_deslocamento' => 'integer',
        'distancia' => 'decimal:2',
    ];

    protected $appends = [
        'dia_formatado',
        'dia_da_semana',
        'dia_da_semana_index',
        'valor_formatado',
        'taxa_de_deslocamento_formatada',
        'tempo_de_deslocamento_formatado',
        'distancia_formatada'
    ];

    protected static function booted(): void
    {
        static::creating(function (Aula $aula) {
            if ($aula->turma_id && ! $aula->endereco_id) {
                $turma = Turma::find($aula->turma_id);
                $aula->endereco_id = $turma?->endereco_id;
            }
        });
    }

    function getDiaFormatadoAttribute()
    {
        return Formata::data($this->dia);
    }

    public function getValorFormatadoAttribute()
    {
        return "R$ " . number_format($this->valor, 2, ',', '.');
    }

    public function getDistanciaFormatadaAttribute()
    {
        return number_format($this->distancia, 2, ',', '.') . " Km";
    }

    public function getTaxaDeDeslocamentoFormatadaAttribute()
    {
        return "R$ " . number_format($this->taxa_de_deslocamento, 2, ',', '.');
    }

    public function getTempoDeDeslocamentoFormatadoAttribute()
    {
        return $this->tempo_de_deslocamento . " min.";
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

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function alunos()
    {
        // remover após aajustar pro adrão
        return $this->belongsToMany(Aluno::class, 'alunos_aulas')->withTimestamps();
    }
}
