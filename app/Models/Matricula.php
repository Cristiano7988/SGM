<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'situacao_id',
        'marcacao_id',
        'pacote_id',
    ];

    protected $with = [
        'users'
    ];

    protected $appends = [
        'vencida'
    ];

    public function getVencidaAttribute():bool
    {
        return $this->pacote->vencido ;
    }


    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function marcacao()
    {
        return $this->belongsTo(Marcacao::class);
    }

    public function situacao()
    {
        return $this->belongsTo(Situacao::class);
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}
