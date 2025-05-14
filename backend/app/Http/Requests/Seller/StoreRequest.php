<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:sellers,email',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'name.string' => 'O nome deve ser uma string',
            'email.required' => 'O email é obrigatório',
            'email.unique' => 'O email já está em uso',
            'email.email' => 'O email não é válido',
        ];
    }

    public function name(): string
    {
        return $this->validated('name');
    }

    public function email(): string
    {
        return $this->validated('email');
    }

}
