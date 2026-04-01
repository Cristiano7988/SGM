<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pacote extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'valor',
        'ativo',
        'turma_id',
    ];

    protected $with = [
        'aulas'
    ];

    protected $appends = [
        'valor_formatado',
        'aulas_na_semana',
        'aulas_na_semana_index',
        'vigencia',
        'vencido',
        'vagas_preenchidas',
        'total_de_aulas'
    ];

    public function getValorFormatadoAttribute()
    {
        return "R$ " . number_format($this->attributes['valor'], 2, ',', '.');
    }
    
    public function getAulasNaSemanaAttribute()
    {
        return $this->aulas->unique(function ($aula) {
            return $aula->dia_da_semana;
        })->values();
    }

    public function getAulasNaSemanaIndexAttribute()
    {
        return $this->aulas->min('dia_da_semana_index');
    }

    public function getVigenciaAttribute()
    {
        $inicio = $this->aulas->first();
        $fim = $this->aulas->last();

        if (!$inicio && !$fim) return "Indefinida";
        
        if ($inicio == $fim) return $inicio->dia_formatado;
    
        return "De {$inicio->dia_formatado} até {$fim->dia_formatado}";
    }

    public function getVencidoAttribute():bool
    {
        $fim = $this->aulas->sortBy('dia')->last();
        if (!$fim) return false;

        return Carbon::parse($fim->dia)->isPast();
    }

    function getVagasPreenchidasAttribute(): int
    {
        return $this->matriculas()->count();
    }

    function getTotalDeAulasAttribute(): int
    {
        return $this->aulas()->count();
    }

    public static function disponiveis()
    {
        return Pacote::has('aulas')
            ->with('turma')
            ->get()
            ->filter(function ($pacote) {
                return $pacote->turma->vagas_preenchidas < $pacote->turma->vagas_ofertadas;
            })
            ->sortBy('aulas_na_semana_index')
            ->values();
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}
