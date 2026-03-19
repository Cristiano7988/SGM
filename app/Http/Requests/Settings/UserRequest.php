<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        if ($this->input('alunos')) {
            $this->merge([
                'alunos' => collect($this->input('alunos'))
                    ->unique('id')
                    ->mapWithKeys(function ($aluno) {
                        return [
                            $aluno['id'] => [
                                'vinculo' => $aluno['pivot']['vinculo']
                            ]
                        ];
                    })
                    ->toArray()
            ]);
        }

        $id = $this->input('id');
            
        return !request()->password
            ? [
                'nome' => ['required', 'string', 'min:2', 'max:255'],
                'email' => [$user ? 'nullable' : 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
                'email_nf' => ['string', 'email', 'max:255'],
                'cpf' => ['nullable', 'string', 'min:11', 'max:14', Rule::unique('users')->ignore($id)],
                'cnpj' => ['nullable', 'string', 'min:14', 'max:18', Rule::unique('users')->ignore($id)],
                'whatsapp' => ['string', 'min:3', 'max:100', Rule::unique('users')->ignore($id)],
                'instagram' => ['nullable', 'string', 'url', 'min:5', 'max:255', Rule::unique('users')->ignore($id)],
                'cep' => ['nullable', 'string', 'min:8', 'max:9'],
                'pais' => ['string', 'min:2', 'max:50'],
                'estado' => ['string', 'min:2', 'max:70'],
                'cidade' => ['string', 'min:2', 'max:255'],
                'bairro' => ['string', 'min:2', 'max:255'],
                'logradouro' => ['string', 'min:2', 'max:255'],
                'numero' => ['integer', 'min:1', 'max:999999'],
                'complemento' => ['string', 'min:1', 'max:999999'],
                'alunos' => ['array'],
                'alunos.*.aluno_id' => ['integer', 'exists:alunos,id'],
                'alunos.*.vinculo' => ['string', 'nullable'],
            ]
            : [
                'nome' => ['required', 'string', 'min:2', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed']
            ];
    }
}
