<x-app-layout>
    <div class="container mx-auto p-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">注文の確定</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="font-bold mb-4 border-b pb-2">注文内容の確認</h2>

            <table class="w-full mb-6">
                <thead>
                    <tr class="text-left text-sm text-gray-500">
                        <th class="pb-2">商品名</th>
                        <th class="pb-2">サイズ</th>
                        <th class="pb-2">数量</th>
                        <th class="pb-2">価格</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- $order->details ではなく $cartDetails をループさせる --}}
                    @foreach($cartDetails as $detail)
                        <tr class="border-b last:border-0">
                            <td class="py-3">{{ $detail->product->name }}</td>
                            <td class="py-3">
                                @if($detail->productSize){{ $detail->productSize->size->size_name ?? '-' }}cm</td>
                                @endif
                            <td class="py-3">{{ $detail->quantity }}</td>
                            <td class="py-3">¥{{ number_format($detail->product->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right mb-6">
                <span class="text-gray-600">合計金額:</span>
                <span class="text-2xl font-bold text-red-600 ml-2">¥{{ number_format($totalAmount) }}</span>
            </div>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                {{-- 二重送信防止用キー (ControllerでCache::putしているもの) --}}
                <input type="hidden" name="idempotency_key" value="{{ uniqid('order_', true) }}">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">配送方法</label>
                    <select name="delivery_method" class="w-full border rounded p-2">
                        <option value="1">通常配送</option>
                        <option value="2">学校受取</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">支払い方法</label>
                    <select name="payment_method" class="w-full border rounded p-2">
                        <option value="1">クレジットカード</option>
                        <option value="2">PayPay</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-200">
                    注文を確定する
                </button>
            </form>
        </div>
    </div>
</x-app-layout>