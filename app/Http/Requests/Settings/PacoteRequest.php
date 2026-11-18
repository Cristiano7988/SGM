<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PacoteRequest extends FormRequest
{
    /**
     * Prepara e limpa os dados aninhados antes da validação.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('aulas') && is_array($this->input('aulas'))) {
            $aulasFormatadas = collect($this->input('aulas'))
                ->map(function ($aula) {
                    $alunoIds = collect($aula['alunos'] ?? [])
                        ->pluck('id')
                        ->filter(fn ($id) => !empty($id) && is_numeric($id) && $id > 0)
                        ->unique()
                        ->values()
                        ->toArray();

                    $aula['aluno_ids'] = $alunoIds;

                    return $aula;
                })
                ->toArray();

            $this->merge([
                'aulas' => $aulasFormatadas,
            ]);
        }
    }

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
            'valor' => ['required', 'numeric', 'min:0'],
            'aulas' => ['array'],
            'aulas.*.id' => ['nullable', 'integer', 'exists:aulas,id'],
            'aulas.*.dia' => [
                'required',
                'date_format:Y-m-d',
                Rule::when(! $user?->is_admin, ['after_or_equal:today']),
            ],
            'aulas.*.horario' => ['required', 'date_format:H:i'],
            'aulas.*.turma_id' => ['required', 'numeric', 'exists:turmas,id'],
            'aulas.*.endereco_id' => ['required', 'numeric', 'exists:enderecos,id'],
            'aulas.*.valor' => ['required', 'numeric', 'min:0'],
            'aulas.*.taxa_de_deslocamento' => ['nullable', 'numeric', 'min:0'],
            'aulas.*.tempo_de_deslocamento' => ['nullable', 'numeric', 'min:0'],
            'aulas.*.distancia' => ['nullable', 'numeric', 'min:0'],

            // Valida os IDs extraídos de cada aula
            'aulas.*.aluno_ids' => ['nullable', 'array'],
            'aulas.*.aluno_ids.*' => ['integer', 'exists:alunos,id'],
        ];
    }
}
