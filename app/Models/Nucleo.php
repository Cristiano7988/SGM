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
        'inicio_rematricula',
        'fim_rematricula'
    ];

    /**
     * Mutators
     */

    protected $appends = ['unidade_de_tempo_minima', 'unidade_de_tempo_maxima'];

    function getUnidadeDeTempoMinimaAttribute()
    {
        return $this->attributes['idade_minima'] > 12 ? 'anos' : 'meses';
    }

    function getUnidadeDeTempoMaximaAttribute()
    {
        return $this->attributes['idade_maxima'] > 12 ? 'anos' : 'meses';
    }

    function getInicioRematriculaAttribute($value)
    {
        return Formata::data($value);
    } 

    function getFimRematriculaAttribute($value)
    {
        return Formata::data($value);
    }

    function getDescricaoAttribute(string $value): array
    {
        return explode("\n\n", $value);
    }

    function setDescricaoAttribute(string $value)
    {
        return $this->attributes['descricao'] = $value;
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
