<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;
    protected $table = "transacoes";
    public $with = ['forma_de_pagamento'];
    protected $fillable = [
        'user_id', // se for excluir o usuário verificar (no FE) se há transações ou alunos associadas à transação e pedir consentimento do usuário
        'matricula_id',
        'cupom_id', // Desativar ao invés de excluir, se for excluir verificar (no FE) se há transações associadas ao cupom e pedir consentimento do usuário
        'forma_de_pagamento_id',
        'comprovante',
        'valor_pago',
        'data_de_pagamento',
        'obs',
        'forma_de_pagamento',
        'nome_do_aluno',
        'nome_do_usuario',
        'nome_do_pacote',
        'vigencia_do_pacote',
        'valor_do_pacote',
    ];

    public function forma_de_pagamento()
    {
        return $this->belongsTo(FormaDePagamento::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cupom()
    {
        return $this->belongsTo(Cupom::class);
    }
}
