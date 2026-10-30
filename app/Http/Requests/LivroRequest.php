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

    protected function prepareForValidation(): void
    {
        // Sanitiza a string de moeda (ex: "R$ 1.250,50" -> "1250.50")
        if ($this->has('valor')) {
            $valorSanitizado = str_replace(['R$', ' ', '.'], '', $this->valor);
            $valorSanitizado = str_replace(',', '.', $valorSanitizado);

            $this->merge([
                'valor' => (float) $valorSanitizado,
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
            'autores.*' => ['exists:autores,id'],
            'assuntos' => ['required', 'array', 'min:1'],
            'assuntos.*' => ['exists:assuntos,id'],
        ];
    }
}
