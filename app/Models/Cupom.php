<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;
    protected $table = 'cupons';
    protected $fillable = [
        'codigo',
        'desconto',
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
