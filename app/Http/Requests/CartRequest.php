<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            //store用
            'product_id' => 'required|exists:products,id',
            'product_size_id' => 'nullable|exists:product_sizes,id',
            'quantity' => 'required|integer|min:1',
            //storeKit用
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.product_size_id' => 'nullable|exists:product_sizes,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}
