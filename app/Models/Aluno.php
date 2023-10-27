<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'data_de_nascimento'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class);
    }

    public function matriculas()
    {
        return $this->belongsToMany(Matricula::class);
    }
}
