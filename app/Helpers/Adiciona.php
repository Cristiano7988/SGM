<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class Adiciona
{
    public static function modelRelacionada($table, $query, $column, $model)
    {
        $table = $query == '*'
            ? $table->with($model)
            : $table->with($model)->whereIn($column, explode(',', $query));
        
        return $table;
    }

    public static function matriculas(Request $request, $parentModel, $single = false)
    {
        $method = $single ? 'matricula' : 'matriculas';
        $parentModel = $parentModel->with([
            $method => function ($matricula) use ($request) {
                if ($request['turmas']) $matricula = $matricula->with([
            
                    'turma' => function ($turma) use ($request) {
                        if ($request['nucleos']) $turma = Adiciona::modelRelacionada($turma, $request['nucleos'], 'nucleo_id', 'nucleo');
                        if ($request['dias']) $turma = Adiciona::modelRelacionada($turma, $request['dias'], 'dia_id', 'dia');
                        if ($request['status']) $turma = Adiciona::modelRelacionada($turma, $request['status'], 'status_id', 'status');
                        return $turma;
                    }
                ]);
                if ($request['situacoes']) $matricula = Adiciona::modelRelacionada($matricula, $request['situacoes'], 'situacao_id', 'situacao');
                if ($request['marcacoes']) $matricula = Adiciona::modelRelacionada($matricula, $request['marcacoes'], 'marcacao_id', 'marcacao');
                if ($request['pacotes']) $matricula = $matricula->with([
                    'pacote' => function ($pacote) use ($request) {
                        if ($request['periodos']) $pacote = $pacote->with(['periodos']);
                        if ($request['nucleos']) $pacote = Adiciona::modelRelacionada($pacote, $request['nucleos'], 'nucleo_id', 'nucleo');
                        return $pacote;
                    }
                ]);
                if ($request['transacoes'] && !$request['que_fizeram_transacao']) $matricula = $matricula->with(['transacoes']);
                return $matricula;
            }
        ]);

        return $parentModel;
    }
}
