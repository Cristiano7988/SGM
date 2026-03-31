<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Helpers\Formata;

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
        'meses',
        'anos'
    ];

    public function getDataDeNascimentoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getDataDeNascimentoFormatadaAttribute()
    {
        return Formata::data($this->data_de_nascimento);
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

    public function getMesesAttribute()
    {
        $data = Carbon::parse($this->attributes['data_de_nascimento']);

        return $data->floatDiffInMonths(now());
    }

    public function getAnosAttribute()
    {
        $data = Carbon::parse($this->attributes['data_de_nascimento']);

        return $data->floatDiffInYears(now());
    }

    public static function allWithHisPivot($userId)
    {
        return self::get()
            ->map(function ($aluno) use ($userId) {
                $aluno->pivot = $aluno->users->find($userId)->pivot ?? (object)['id' => 0, 'vinculo' => "", 'user_id' => 0, 'aluno_id' => 0];
                return $aluno;
            });
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('vinculo')->withTimestamps();
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
