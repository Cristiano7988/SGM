<?php

namespace App\Helpers;

use App\Models\Aluno;
use App\Models\Cupom;
use App\Models\FormaDePagamento;
use App\Models\Matricula;
use App\Models\Nucleo;
use App\Models\Pacote;
use App\Models\Periodo;
use App\Models\Transacao;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Substitui
{   
    public static function comDados(string $parent, Model $model, string $conteudo): string
    {
        $propriedades = array_keys($model->getAttributes());

        foreach ($propriedades as $propriedade) {
            $nome_composto_por = explode('_', $propriedade);
            $hasChild = in_array('id', $nome_composto_por) && $propriedade !== 'zoom_id' && count($nome_composto_por) > 1;
            
            if ($hasChild) {
                $index = array_search('id', $nome_composto_por);
                unset($nome_composto_por[$index]);

                $child = implode('_', $nome_composto_por);
                $childTag = implode('-', [$parent, $child]);
                if ($model->$child) $conteudo = Substitui::comDados($childTag, $model->$child, $conteudo);
            }
    
            $tag = '{{' . $parent . '-' . $propriedade . '}}';
            $dado = Formata::sePrecisa($propriedade, $model[$propriedade], $model);
            $conteudo = str_replace($tag, $dado, $conteudo);
        }

        return $conteudo;
    }

    public static function masAntesChecaSePrecisa(Request $request, $conteudo)
    {
        $aluno = Aluno::find($request->aluno_id) ?? null;
        $cupom = Cupom::find($request->cupom_id) ?? null;
        $forma_de_pagamento = FormaDePagamento::find($request->forma_de_pagamento_id) ?? null;
        $matricula = Matricula::find($request->matricula_id) ?? null;
        $nucleo = Nucleo::find($request->nucleo_id) ?? null;
        $pacote = Pacote::find($request->pacote_id) ?? null;
        $periodo = Periodo::find($request->periodo_id) ?? null;
        $transacao = Transacao::find($request->transacao_id) ?? null;
        $turma = Turma::find($request->turma_id) ?? null;
        $user = User::find($request->user_id) ?? null;

        if ($aluno) $conteudo = Substitui::comDados('aluno', $aluno, $conteudo);
        if ($cupom) $conteudo = Substitui::comDados('cupom', $cupom, $conteudo);
        if ($forma_de_pagamento) $conteudo = Substitui::comDados('forma_de_pagamento', $forma_de_pagamento, $conteudo);
        if ($matricula) $conteudo = Substitui::comDados('matricula', $matricula, $conteudo);
        if ($nucleo) $conteudo = Substitui::comDados('nucleo', $nucleo, $conteudo);
        if ($pacote) $conteudo = Substitui::comDados('pacote', $pacote, $conteudo);
        if ($periodo) $conteudo = Substitui::comDados('periodo', $periodo, $conteudo);
        if ($transacao) $conteudo = Substitui::comDados('transacao', $transacao, $conteudo);
        if ($turma) $conteudo = Substitui::comDados('turma', $turma, $conteudo);
        if ($user) $conteudo = Substitui::comDados('user', $user, $conteudo);

        return $conteudo;
    }
}