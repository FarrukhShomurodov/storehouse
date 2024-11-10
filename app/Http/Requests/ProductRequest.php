<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:600',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
        ];
    }
}
