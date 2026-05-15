<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolKitRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'スクールキット名は必須です。',
            'product_id.required' => '商品は必ず1つ以上選択してください。',
            'product_id.exists' => '選択された商品は存在しません。',
        ];
    }
}
