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
        'datas'
    ];

    protected $appends = [
        'valor_formatado',
        'vigencia'
    ];

    public function getValorFormatadoAttribute()
    {
        return "R$ " . number_format($this->attributes['valor'], 2, ',', '.');
    }

    public function getVigenciaAttribute()
    {
        $inicio = $this->datas->first();
        $fim = $this->datas->last();

        if (!$inicio && !$fim) return "Indefinida";
        
        if ($inicio == $fim) return $inicio->dia_formatado;
    
        return "De {$inicio->dia_formatado} até {$fim->dia_formatado}";
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function datas()
    {
        return $this->hasMany(Data::class);
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
