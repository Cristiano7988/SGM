<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TurmaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules($turma = false): array
    {
        return [
            'nome' => [
                'string',
                'required',
                'min:3',
                'max:30',
            ],
            'descricao' => 'string|min:10|max:1500',
            'imagem' => ['required', function($attribute, $value, $fail) {
                validaImagem($attribute, $value, $fail);
            }],
            'vagas_ofertadas' => ['required', 'numeric'],
            'disponivel' => 'boolean',
            'zoom' => ['nullable', 'url'],
            'zoom_id' => ['nullable', 'string'],
            'zoom_senha' => ['nullable', 'string', 'min:3'],
            'whatsapp' => ['nullable', 'url'],
            'spotify' => ['nullable', 'url'],
            'nucleo_id' => ['nullable', 'numeric', 'min:1', 'exists:nucleos,id'],
        ];
    }
}
