<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssuntoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao' => ['required', 'string', 'max:20'],
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'descricao.required' => 'O campo Descrição do Assunto é obrigatório.',
            'descricao.string' => 'A Descrição do Assunto deve ser um texto válido.',
            'descricao.max' => 'A Descrição do Assunto não pode ter mais de 20 caracteres.',
        ];
    }
}
