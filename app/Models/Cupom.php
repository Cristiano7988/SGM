<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;
    protected $table = 'cupons';
    public $with = ['medida'];
    protected $fillable = [
        'codigo',
        'desconto',
        'ativo', // Desativar ao invés de excluir, se for excluir verificar (no FE) se há transações associadas ao cupom e pedir consentimento do cliente e mesmo assim enviar as informações do cupom excluído ao cliente
        'medida_id'
    ];

    public function medida()
    {
        return $this->belongsTo(Medida::class);
    }

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}
