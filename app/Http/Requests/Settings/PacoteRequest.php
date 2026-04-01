<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PacoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'nome' => ['required', 'string', 'min:3', 'max:255'],

            'valor' => [
                'required',
                'numeric',
                'min:0',
            ],

            'ativo' => ['required', 'boolean'],
            'turma_id' => [
                'required',
                'numeric',
                'exists:turmas,id',
            ],
            'aulas' => ['array'],
            'aulas.*.dia' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'aulas.*.horario' => ['required', 'date_format:H:i'],
        ];
    }
}
