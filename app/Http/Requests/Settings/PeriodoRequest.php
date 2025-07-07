<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PeriodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inicio' => [
                'required',
                'date_format:Y-m-d', // Assuming the date format is YYYY-MM-DD
                'before_or_equal:fim', // Ensure 'inicio' is before or equal to 'fim'
            ],

            'fim' => [
                'required',
                'date_format:Y-m-d', // Assuming the date format is YYYY-MM-DD
                'after_or_equal:inicio', // Ensure 'fim' is after or equal to 'inicio'
            ],

            'pacote_id' => [
                'required',
                'numeric',
                'exists:pacotes,id',
            ],
        ];
    }
}
