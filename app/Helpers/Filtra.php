<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class Filtra
{
    public static function resultado(Builder $table, string $query, string $column)
    {
        $table = $query == '*'
            ? $table->whereNotNull($column)
            : $table->whereNotNull($column)->whereIn($column, explode(',', $query));
        
        return $table;
    }
}
