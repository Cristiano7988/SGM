<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaDePagamento extends Model
{
    use HasFactory;
    protected $table = 'formas_de_pagamento';
    protected $fillable = [
        'tipo'
    ];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class);
    }
}
