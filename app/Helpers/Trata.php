<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class Trata
{
    public static function resultado(Builder $table, string $padrao)
    {
        extract(request()->all());

        $order_by = $order_by ?? $padrao;
        $sort = $sort ?? 'asc';
        $per_page = $per_page ?? 10;

        $table = $table->orderBy($order_by, $sort)->paginate($per_page);
        
        return $table;
    }
}
