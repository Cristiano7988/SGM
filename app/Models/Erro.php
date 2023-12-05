<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Erro extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'rota',
        'metodo',
        'acessado_via',
        'corpo_da_requisicao',
        'mensagem',
        'arquivo',
        'linha'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
