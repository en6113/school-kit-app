<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        // ログインしていればOKとする
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            // 配送方法 (1:通常, 2:学校)
            'delivery_method' => ['required', 'in:1,2'],

            // 支払い方法 (1:クレカ, 2:PayPay)
            'payment_method' => ['required', 'in:1,2'],

            // 二重送信防止用
            'idempotency_key' => ['required', 'string'],
        ];
    }

    /**
     * エラーメッセージの日本語化（任意）
     */
    public function messages(): array
    {
        return [
            'delivery_method.required' => '配送方法を選択してください。',
            'payment_method.required' => '支払い方法を選択してください。',
        ];
    }
}