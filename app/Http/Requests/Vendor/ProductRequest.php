<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_sizes.*' => 'nullable|exists:product_sizes,id',
            'starter_kits.*' => 'nullable|exists:starter_kits,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名は必須です。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0以上の数値を入力してください。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上の数値を入力してください。',
            'category_id.required' => 'カテゴリは必須です。',
            'category_id.exists' => '選択されたカテゴリは存在しません。',
            'product_images.*.image' => 'アップロードされたファイルは画像である必要があります。',
            'product_images.*.mimes' => 'アップロード可能な画像形式はjpeg, png, jpg, gif, svgです。',
            'product_images.*.max' => '画像のファイルサイズは2MB以内にしてください。',
            'product_sizes.*.exists' => '選択されたサイズは存在しません。',
            'starter_kits.*.exists' => '選択されたスターターキットは存在しません。',
        ];
    }
}