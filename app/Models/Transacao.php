<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;
    protected $table = "transacao";
    protected $fillable = [
        'user_id',
        'matricula_id',
        'cupom_id',
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
        return $this->belongsTo(Cupon::class);
    }
}
