<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;
    public $with = ['tipo_de_aula', 'dia'];
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

    protected $casts = [
        'disponivel' => 'boolean',
        'vagas_fora_do_site' => 'integer',
        'vagas_ofertadas' => 'integer',
        'vagas_preenchidas' => 'integer',
        'descricao' => 'string'
    ];

    protected $appends = ['vagas_preenchidas'];

    function getDescricaoAttribute(string $value): array
    {
        return explode("\n\n", $value);
    }

    function getVagasPreenchidasAttribute(): int
    {
        return $this->matriculas()->count();
    }

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
