<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class EnderecoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->input('alunos')) {
            $this->merge([
                'alunos' => collect($this->input('alunos'))
                    ->unique('id')
                    ->mapWithKeys(function ($aluno) {
                        return [
                            $aluno['id'] => $aluno['id']
                        ];
                    })
                    ->toArray()
            ]);
        }

        return [
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:10'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'max:2'],
            'pais' => ['required', 'string', 'max:255'],
            'cep' => ['required', 'string', 'max:9'],
            'alunos' => ['nullable', 'array'],
            'alunos.*.aluno_id' => ['integer', 'exists:alunos,id'],
        ];
    }
}
