<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class ProfessorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->input('users')) {
            $this->merge([
                'users' => collect($this->input('users'))
                    ->unique('id')
                    ->mapWithKeys(function ($user) {
                        return [
                            $user['id'] => [
                                'mesma_pessoa' => isset($user['pivot']) && $user['pivot']['mesma_pessoa']
                            ]
                        ];
                    })
                    ->toArray()
            ]);
        }

        return [
            'nome' => 'required|string|max:100',
            'imagem' => ['required', function($attribute, $value, $fail) {
                validaImagem($attribute, $value, $fail);
            }],
        ];
    }
}
