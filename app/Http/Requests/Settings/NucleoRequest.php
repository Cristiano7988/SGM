<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NucleoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules($nucleo = false): array
    {
        // Transformar request em um array de integers
        if ($this->has('turmas')) {
            $this->merge([
                'turmas' => array_map(function ($item) {
                    return is_array($item) && isset($item['id']) ? $item['id'] : $item;
                }, $this->input('turmas')),
            ]);
        }

        if ($this->has('pacotes')) {
            $this->merge([
                'pacotes' => array_map(function ($item) {
                    return is_array($item) && isset($item['id']) ? $item['id'] : $item;
                }, $this->input('pacotes')),
            ]);
        }

        return [
            'nome' => [
                'string',
                'required',
                'min:3',
                'max:30',
                $nucleo ? "unique:nucleos,nome,{$nucleo}" : ''
            ],
            'descricao' => 'string|required|min:10|max:1500',
            'imagem' => ['required', function ($attribute, $value, $fail) {
                $isUrl = filter_var($value, FILTER_VALIDATE_URL);
                $isFile = is_file($value);

                // Lista de extensões permitidas
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                // Se for um arquivo, verificar a extensão
                if ($isFile) {
                    $extension = $value->getClientOriginalExtension();
                    
                    if (!in_array(strtolower($extension), $allowedExtensions)) {
                        $fail($attribute . ' deve ser uma imagem válida (jpg, jpeg, png, gif, webp).');
                    }
                }

                // Se for uma URL, garantir que termina com uma extensão permitida
                if ($isUrl) {
                    $path = parse_url($value, PHP_URL_PATH);
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    if (!in_array(strtolower($extension), $allowedExtensions)) {
                        $fail($attribute . ' deve ser uma URL de imagem válida (jpg, jpeg, png, gif, webp).');
                    }
                }

                if (!$isUrl && !$isFile) $fail($attribute.' deve ser uma URL válida ou um arquivo válido.');
            }],
            'idade_minima' => 'required|numeric|min:1|max:1320',
            'idade_maxima' => "required|numeric|min:{$this->input('idade_minima')}|max:1320",
            'inicio_matricula' => "required|date|date_format:Y-m-d|before_or_equal:fim_matricula",
            'fim_matricula' => "required|date|date_format:Y-m-d|after_or_equal:inicio_matricula",
            'turmas' => 'array',
            'pacotes' => 'array'
        ];
    }
}
