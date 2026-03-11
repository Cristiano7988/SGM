<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getDataDeNascimentoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getDataDeNascimentoFormatadaAttribute()
    {
        if (!$this->data_de_nascimento) return null;

        return Carbon::parse($this->data_de_nascimento)->format('d/m/Y');
    }

    public function getIdadeAttribute()
    {   
        if (!$this->data_de_nascimento) return null;

        $anos = Carbon::parse($this->data_de_nascimento)->diffInDays(Carbon::now()) / 365;

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
