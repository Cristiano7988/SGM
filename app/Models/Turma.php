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
        'vagas_disponíveis',
        'horario',
        'disponivel',
        'zoom',
        'zoom_id',
        'zoom_senha',
        'whatsapp',
        'spotify',
        'nucleo_id',
        'dia_id',
        'status_id'
    ];

    public function nucleo()
    {
        return $this->belongsTo(Nucleo::class);
    }

    public function dia()
    {
        return $this->belongsTo(Dia::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class);
    }
}
