<?php

namespace App\Helpers;

class Filtra
{
    public static function resultado($table, $query, $column)
    {
        $table = $query == '*'
            ? $table->whereNotNull($column)
            : $table->whereNotNull($column)->whereIn($column, explode(',', $query));
        
        return $table;
    }
}
