<x-app-layout>
    <div class="container mx-auto p-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">注文詳細 #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:underline">← 履歴に戻る</a>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">注文日時</p>
                    <p class="font-medium">{{ $order->created_at->format('Y/m/d H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">ステータス</p>
                    <p class="font-medium">
                        {{ $order->status == 1 ? '準備中' : ($order->status == 0 ? 'キャンセル済み' : '完了') }}
                    </p>
                </div>
            </div>

            <h3 class="font-bold border-b pb-2 mb-4">注文商品</h3>
            <ul class="divide-y">
                @foreach($order->details as $detail)
                    <li class="py-4 flex justify-between">
                        <div>
                            <p class="font-bold">{{ $detail->product->name }}</p>
                            <p class="text-sm text-gray-500">
                                サイズ: {{ $detail->productSize->size->size_name ?? '-' }}cm/単価: ¥{{ number_format($detail->price_at_purchase) }} / 数量:
                                {{ $detail->quantity }}
                            </p>
                        </div>
                        <p class="font-bold">
                            ¥{{ number_format($detail->price_at_purchase * $detail->quantity) }}</p>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 text-right">
                <p class="text-gray-500">合計金額</p>
                <p class="text-2xl font-bold text-red-600">¥{{ number_format($order->total_amount) }}</p>
            </div>
        </div>

        {{-- キャンセルボタン --}}
        @if($order->status == 1 && $order->user_id === auth()->id())
            <div class="flex justify-end">
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                    onsubmit="return confirm('本当にこの注文をキャンセルしますか？');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow">
                        この注文をキャンセルする
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>