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

    /* Os cupons estão em uma tabela independente, sem relação com pacote, turma ou núcleo */
    /* Caso haja alguma condição de validação do cupom na realização da transação referente ao pacote, turma ou núcleo escolhido a devida relação deve ser aplicada */
}
