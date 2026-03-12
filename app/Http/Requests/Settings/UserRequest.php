<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules($user = false): array
    {
        // Transformar request em um array de integers
        if ($this->has('alunos')) {
            $this->merge([
                'alunos' => array_map(function ($item) {
                    return is_array($item) && isset($item['id']) ? $item['id'] : $item;
                }, $this->input('alunos')),
            ]);
        }

        return [
            'nome' => [
                $user ? 'nullable' : 'required',
                'string',
                'min:2',
                'max:255'
            ],
            // 'email' => [
            //     $user ? 'nullable' : 'required',
            //     'email',
            //     'max:255'
            // ],
            // 'password' => [
            //     $user ? 'nullable' : 'required',
            //     'string',
            //     'min:8',
            //     'confirmed'
            // ],
            'email_nf' => [
                'required',
                'email',
                'max:255'
            ],
            'cpf' => [
                'nullable',
                // 'required',
                'string',
                'size:14'
            ],
            'cnpj' => [
                'nullable',
                // 'required',
                'string',
                'size:18'
            ],
            // 'vinculo' => [
            //     'required',
            //     'string',
            //     'max:255'
            // ],
            'whatsapp' => [
                'nullable',
                'string',
                'max:255'
            ],
            'instagram' => [
                'nullable',
                'string',
                'max:255'
            ],
            'cep' => [
                'nullable',
                'string',
                'size:9'
            ],
            'pais' => [
                'nullable',
                'string',
                'max:255'
            ],
            'estado' => [
                'nullable',
                'string',
                'max:255'
            ],
            'cidade' => [
                'nullable',
                'string',
                'max:255'
            ],
            'bairro' => [
                'nullable',
                'string',
                'max:255'
            ],
            'logradouro' => [
                'nullable',
                'string',
                'max:255'
            ],
            'numero' => [
                'nullable',
                'integer',
                'max:255'
            ],
            'complemento' => [
                'nullable',
                'string',
                'max:255'
            ],
            'alunos.*' => ['integer', 'exists:alunos,id'],
        ];
    }
}
