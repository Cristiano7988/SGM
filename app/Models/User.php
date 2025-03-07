<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $with = ['tipos'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'password',
        'email_nf',
        'cpf', // Rever formatação
        'cnpj', // Rever formatação
        'vinculo',
        'whatsapp',
        'instagram',
        'cep',
        'pais',
        'estado',
        'cidade',
        'bairro',
        'logradouro',
        'numero',
        'complemento'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tipos()
    {
        return $this->belongsToMany(Tipo::class);
    }

    public function alunos()
    {
        return $this->belongsToMany(Aluno::class)->withTimestamps();
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }

    public function emails()
    {
        return $this->belongsToMany(Email::class)->withTimestamps();
    }

    public function erros()
    {
        return $this->hasMany(Erro::class);
    }
}
