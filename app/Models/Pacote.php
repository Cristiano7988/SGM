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
        'nucleo_id',
    ];

    protected $appends = [
        'valor_formatado',
    ];

    public function getValorFormatadoAttribute()
    {
        return "R$ " . number_format($this->attributes['valor'], 2, ',', '.');
    }

    public function nucleo()
    {
        return $this->belongsTo(Nucleo::class);
    }

    public function periodos()
    {
        return $this->hasMany(Periodo::class);
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
