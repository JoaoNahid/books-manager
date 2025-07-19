<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'published_at' => 'required|date',
            'author_id' => 'required|exists:authors,id',
            'book_id' => 'nullable|exists:books,id',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',
            'description.required' => 'A descrição é obrigatória.',
            'published_at.required' => 'A data de publicação é obrigatória.',
            'published_at.date' => 'A data de publicação deve ser uma data válida.',
            'author_id.required' => 'O autor é obrigatório.',
            'author_id.exists' => 'O autor selecionado não é válido.',
        ];
    }
}
