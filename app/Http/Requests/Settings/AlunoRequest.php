<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AlunoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules($aluno = false): array
    {
        return [
            'nome' => [
                $aluno ? 'nullable' : 'required',
                'string',
                'min:2',
                'max:255'],
            'data_de_nascimento' => [
                $aluno ? 'nullable' : 'required',
                'date',
                'date_format:Y-m-d',
                'before:'.date('d/m/Y', strtotime('-30 days')) // Deve ter no mínimo 1 mês de idade
            ],
            'users' => [
                'exists:users,id',
            ]
        ];
    }
}
