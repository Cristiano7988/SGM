<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MatriculaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules($aluno = false): array
    {
        if ($this->has('users')) {
            $this->merge([
                'users' => array_map(function ($item) {
                    return is_array($item) && isset($item['id']) ? $item['id'] : $item;
                }, $this->input('users')),
            ]);
        }
                        
        return [
            'aluno_id' => [
                'integer',
                'required',
                'exists:alunos,id'
            ],
            'turma_id' => [
                'integer',
                'required',
                'exists:turmas,id'
            ],
            'pacote_id' => [
                'integer',
                'required',
                'exists:pacotes,id'
            ],
            'situacao_id' => [
                'integer',
                'required',
                'exists:situacoes,id'
            ],
            'marcacao_id' => [
                'integer',
                'required',
                'exists:marcacoes,id'
            ],
            'users' => ['array'],
            'users.*.user_id' => [
                'integer',
                'exists:users,id'
            ]
        ];
    }
}
