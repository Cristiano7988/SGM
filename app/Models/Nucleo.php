<?php

namespace App\Models;

use App\Helpers\Formata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nucleo extends Model
{
    use HasFactory;

    public $with = ['idade_minima', 'idade_maxima'];
    protected $fillable = [
        'nome',
        'imagem',
        'descricao',
        'idade_minima_id',
        'idade_maxima_id',
        'inicio_rematricula',
        'fim_rematricula'
    ];

    /**
     * Mutators
     */

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

    public function idade_minima()
    {
        return $this->belongsTo(IdadeMinima::class);
    }

    public function idade_maxima()
    {
        return $this->belongsTo(IdadeMaxima::class);
    }
}
