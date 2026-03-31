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
        'vagas_ofertadas',
        'disponivel',
        'zoom',
        'zoom_id',
        'zoom_senha',
        'whatsapp',
        'spotify',
        'nucleo_id',
    ];

    protected $casts = [
        'disponivel' => 'boolean',
        'vagas_ofertadas' => 'integer',
        'vagas_preenchidas' => 'integer',
        'descricao' => 'string'
    ];

    protected $appends = ['vagas_preenchidas', 'paragrafos_da_descricao'];

    function getParagrafosDaDescricaoAttribute(): array
    {
        $descricao = $this->attributes['descricao'] ?? "";
        return explode("\n\n", $descricao);
    }

    function getVagasPreenchidasAttribute(): int
    {
        return $this->matriculas()->count();
    }

    public static function allDisponiveis()
    {
        return self::withCount('matriculas')
            ->whereRaw('matriculas_count < vagas_ofertadas')
            ->where('disponivel', true)
            ->get();
    }

    public function nucleo()
    {
        return $this->belongsTo(Nucleo::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function pacotes()
    {
        return $this->hasMany(Pacote::class);
    }
}
