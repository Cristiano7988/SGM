<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    /** @use HasFactory<\Database\Factories\EnderecoFactory> */
    use HasFactory;

    protected $fillable = [
        'cep',
        'pais',
        'estado',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento'
    ];

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class)->withTimestamps();
    }
}
