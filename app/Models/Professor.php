<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    /** @use HasFactory<\Database\Factories\ProfessorFactory> */
    use HasFactory;

    protected $table = 'professores';

    protected $fillable = [
        'nome',
        'imagem',
    ];

    protected $appends = ['user'];

    public function getUserAttribute()
    {
        return $this->users->filter(
            fn ($user) => $user->pivot->mesma_pessoa
        )->values()->first();
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_professores')
            ->withPivot('mesma_pessoa')
            ->withTimestamps();
    }
}
