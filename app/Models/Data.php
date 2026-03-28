<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    
    protected $table = "datas";

    protected $fillable = [
        'dia',
        'pacote_id'
    ];

    protected $casts = [
        'dia' => 'date',
    ];

    protected $appends = [
        'dia',
        'dia_formatado',
    ];

    function getDiaAttribute()
    {
        return Carbon::parse($this->attributes['dia'])->format('Y-m-d');
    }

    function getDiaFormatadoAttribute()
    {
        return Carbon::parse($this->dia)->format('d/m/Y');
    }

    public function pacote()
    {
        return $this->belongsTo(Pacote::class);
    }
}
