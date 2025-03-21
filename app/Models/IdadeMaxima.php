<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdadeMaxima extends Model
{
    use HasFactory;
    protected $table = 'idades_maximas';
    protected $with = ['medida_de_tempo'];
    protected $fillable = [
        'idade',
        'medida_de_tempo_id'
    ];

    public $timestamps = false;
    
    public function nucleos()
    {
        return $this->hasMany(Nucleo::class);
    }

    public function medida_de_tempo()
    {
        return $this->belongsTo(MedidaDeTempo::class);
    }
}
