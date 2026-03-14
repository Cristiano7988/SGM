<?php

namespace App\Models;

use App\Helpers\Formata;
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
        'inicio_matricula',
        'fim_matricula'
    ];

    /**
     * Mutators
     */

    protected $appends = ['unidade_de_tempo_minima', 'unidade_de_tempo_maxima', 'inicio_matricula_formatada', 'fim_matricula_formatada', 'paragrafos_da_descricao'];

    function getUnidadeDeTempoMinimaAttribute()
    {
        return $this->attributes['idade_minima'] > 12 ? 'anos' : 'meses';
    }

    function getUnidadeDeTempoMaximaAttribute()
    {
        return $this->attributes['idade_maxima'] > 12 ? 'anos' : 'meses';
    }

    function getInicioMatriculaFormatadaAttribute()
    {
        return Formata::data($this->attributes['inicio_matricula']);
    } 

    function getFimMatriculaFormatadaAttribute()
    {
        return Formata::data($this->attributes['fim_matricula']);
    }

    function getParagrafosDaDescricaoAttribute(): array
    {
        return explode("\n\n", $this->attributes['descricao']);
    }

    /**
     * Relacionamentos
     */

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }
}
