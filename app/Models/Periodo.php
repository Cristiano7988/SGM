<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
    protected $fillable = [
        'inicio',
        'fim',
        'pacote_id',
    ];

    protected $casts = [
        'inicio' => 'date',
        'fim' => 'date',
    ];

    protected $appends = [
        'inicio',
        'fim',
        'inicio_formatado',
        'fim_formatado',
    ];

    function getInicioAttribute()
    {
        return Carbon::parse($this->attributes['inicio'])->format('Y-m-d');
    }

    function getFimAttribute()
    {
        return Carbon::parse($this->attributes['fim'])->format('Y-m-d');
    }

    function getInicioFormatadoAttribute()
    {
        return Carbon::parse($this->inicio)->format('d/m/Y');
    }

    function getFimFormatadoAttribute()
    {
        return Carbon::parse($this->fim)->format('d/m/Y');
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}
