<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold mb-6 border-b pb-4">買い物かご</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($cart && $cart->cartDetails->isNotEmpty())
            <div class="space-y-4">
                @foreach($cart->cartDetails as $detail)
                    <div class="flex items-center justify-between border-bottom pb-4 border-b">
                        <div class="flex items-center gap-4">
                            <!-- 商品画像 -->
                            <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden">
                                @if($detail->product->productImages->isNotEmpty())
                                    <img src="{{ asset('storage/' . $detail->product->productImages->first()->image_url) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-xs text-gray-400">No Image</div>
                                @endif
                            </div>

                            <!-- 商品情報 -->
                            <div>
                                <h2 class="font-bold text-lg">{{ $detail->product->name }}</h2>
                                <p class="text-sm text-gray-600">
                                    @if($detail->productSize)
                                        サイズ: {{ $detail->productSize->size->size_name }}cm /
                                    @endif
                                    単価: ¥{{ number_format($detail->product->price) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <!-- 数量 -->
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mb-1">数量</p>
                                <span class="font-semibold">{{ $detail->quantity }}</span>
                            </div>

                            <!-- 小計 -->
                            <div class="text-right min-w-[80px]">
                                <p class="text-xs text-gray-500 mb-1">小計</p>
                                <p class="font-bold text-red-600">¥{{ number_format($detail->product->price * $detail->quantity) }}</p>
                            </div>

                            <!-- 削除ボタン -->
                            <form action="{{ route('cart.remove', $detail->id) }}" method="POST" onsubmit="return confirm('カートから削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 border border-red-500 text-red-500 text-xs font-bold rounded hover:bg-red-500 hover:text-white transition-colors">
                                    削除
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 合計金額・アクション -->
            <div class="mt-8 bg-gray-50 p-6 rounded-lg text-right">
                <div class="text-lg mb-4">
                    合計金額: <span class="text-2xl font-bold text-red-600">¥{{ number_format($totalAmount) }}</span>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">
                        買い物を続ける
                    </a>
                    <a href="{{ route('orders.create') }}" class="px-10 py-3 bg-orange-500 text-white rounded-lg font-bold hover:bg-orange-600 shadow-md">
                        注文手続きへ進む
                    </a>
                </div>
            </div>

        @else
            <div class="text-center py-20">
                <p class="text-gray-500 mb-6">カートに商品が入っていません。</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700">
                    商品を探しに行く
                </a>
            </div>
        @endif
    </div>
</x-app-layout>