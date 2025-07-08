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

    protected $casts = [
        'data_de_nascimento' => 'date',
    ];

    protected $appends = [
        'data_de_nascimento_formatada',
        'idade',
    ];

    public function getDataDeNascimentoFormatadaAttribute()
    {
        return $this->data_de_nascimento->format('d/m/Y');
    }

    public function getIdadeAttribute()
    {   
        $anos = $this->data_de_nascimento->diffInDays(now()) / 365;

        $formatado = $anos < 1
            ? number_format($anos, 1, '.', '')
            : number_format(floor($anos), 0, '', '');

        return $formatado . ' anos';
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }


    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
