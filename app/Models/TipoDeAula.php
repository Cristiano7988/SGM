<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDeAula extends Model
{
    use HasFactory;

    protected $table = "tipos_de_aula";
    protected $fillable = [
        "nome"
    ];

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }
}
