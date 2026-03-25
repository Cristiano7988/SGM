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
        // Transformar request em um array de integers
        if ($this->has('periodos')) {
            $this->merge([
                'periodos' => array_map(function ($item) {
                    return is_array($item) && isset($item['id']) ? $item['id'] : $item;
                }, $this->input('periodos')),
            ]);
        }

        return [
            'nome' => ['required', 'string', 'min:3', 'max:255'],

            'valor' => [
                'required',
                'numeric',
                'min:0',
            ],

            'ativo' => ['required', 'boolean'],
            'nucleo_id' => [
                'required',
                'numeric',
                'exists:nucleos,id',
            ],
            'periodos' => ['array'],
            'periodos.*' => ['integer', 'exists:periodos,id'],
        ];
    }
}
