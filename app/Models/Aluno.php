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

    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }


    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
