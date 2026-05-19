<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartKitRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_size_id' => 'nullable|exists:product_sizes,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
