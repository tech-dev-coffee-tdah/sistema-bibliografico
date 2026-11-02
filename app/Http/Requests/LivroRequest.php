<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LivroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Sanitiza os dados ANTES de aplicar as regras de validação.
     * Converte o valor em formato BR (11,11 ou 1.250,50) para o padrão numérico (11.11 ou 1250.50).
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('valor') && is_string($this->valor)) {
            $valorSanitizado = str_replace('.', '', $this->valor);
            $valorSanitizado = str_replace(',', '.', $valorSanitizado);

            $this->merge([
                'valor' => $valorSanitizado,
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
        return [
            'titulo' => ['required', 'string', 'max:40'],
            'editora' => ['required', 'string', 'max:40'],
            'edicao' => ['required', 'integer', 'min:1'],
            'ano_publicacao' => ['required', 'digits:4'],
            'valor' => ['required', 'numeric', 'min:0'],
            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['exists:autors,CodAu'],
            'assuntos' => ['required', 'array', 'min:1'],
            'assuntos.*' => ['exists:assuntos,CodAs'],
        ];
    }
}
