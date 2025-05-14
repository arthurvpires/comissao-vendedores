<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'seller_id' => 'required|exists:sellers,id',
            'value' => 'required|numeric|min:0.01',
            'date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'seller_id.required' => 'O ID do vendedor é obrigatório',
            'seller_id.exists' => 'O ID do vendedor não existe',
            'value.required' => 'O valor da venda é obrigatório',
            'value.numeric' => 'O valor da venda deve ser um número',
            'value.min' => 'O valor da venda deve ser maior que 0',
            'date.required' => 'A data da venda é obrigatória',
            'date.date' => 'A data da venda deve ser uma data válida',
        ];
    }

    public function sellerId(): int
    {
        return $this->validated('seller_id');
    }

    public function value(): int
    {
        return $this->validated('value');
    }

    public function getDate(): string
    {
        return $this->validated('date');
    }


}
