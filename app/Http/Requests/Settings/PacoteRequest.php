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
        $user = auth()->user();

        return [
            'ativo' => ['required', 'boolean'],
            'turma_id' => [
                'required',
                'numeric',
                'exists:turmas,id',
            ],
            'valor' => ['required', 'numeric', 'min:0'],
            'aulas' => ['array'],
            'aulas.*.dia' => ['required', 'date_format:Y-m-d', $user->is_admin ? '' : 'after_or_equal:today'],
            'aulas.*.horario' => ['required', 'date_format:H:i'],
        ];
    }
}
