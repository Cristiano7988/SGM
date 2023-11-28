<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'imagem',
        'vagas_preenchidas',
        'vagas_fora_do_site',
        'vagas_ofertadas',
        'horario',
        'disponivel',
        'zoom',
        'zoom_id',
        'zoom_senha',
        'whatsapp',
        'spotify',
        'nucleo_id',
        'dia_id',
        'tipo_de_aula_id'
    ];

    public function nucleo()
    {
        return $this->belongsTo(Nucleo::class);
    }

    public function dia()
    {
        return $this->belongsTo(Dia::class);
    }

    public function tipo_de_aula()
    {
        return $this->belongsTo(TipoDeAula::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
