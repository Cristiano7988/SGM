<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marcacao extends Model
{
    use HasFactory;

    protected $table = "marcacoes";
    protected $fillable = [
        "observacao",
        "cor",
        "key_code"
    ];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
