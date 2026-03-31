<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'vigencia'
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

    public function getVigenciaAttribute()
    {
        $inicio = $this->aulas->first();
        $fim = $this->aulas->last();

        if (!$inicio && !$fim) return "Indefinida";
        
        if ($inicio == $fim) return $inicio->dia_formatado;
    
        return "De {$inicio->dia_formatado} até {$fim->dia_formatado}";
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
