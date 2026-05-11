<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="md:flex">
                <!-- 画像エリア -->
                <div class="md:w-1/2">
                    @if($product->item_type === 'product' && $product->productImages->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->productImages->first()->image_url) }}"
                            class="w-full rounded-lg">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                            <span class="text-gray-400">No Image</span>
                        </div>
                    @endif
                </div>

                <!-- 基本情報 -->
                <div class="md:w-1/2 md:ml-6 mt-4 md:mt-0">
                    @if($product->item_type === 'starter_kit')
                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded">スターターキット</span>
                    @endif

                    <h1 class="text-3xl font-bold mt-2">{{ $product->name }}</h1>
                    <p class="text-2xl text-red-600 font-bold my-4">¥{{ number_format($product->price) }}</p>
                    <p class="text-sm text-gray-500 mb-4">在庫: {{ $product->stock }}</p>
                    <p class="text-gray-700 mb-6">{{ $product->description }}</p>
                    
                    {{-- サイズ展開がある場合のみ表示 --}}
                    @if(isset($product->productSizes) && $product->productSizes->isNotEmpty())
                        <div class="mb-4">
                            <label class="block font-bold mb-2">サイズ選択</label>
                            <select name="product_size_id" class="w-full border-gray-300 rounded">
                                @foreach($product->productSizes as $size)
                                    <option value="{{ $size->id }}" {{ $size->stock <= 0 ? 'disabled' : '' }}>
                                        {{ $size->size }}cm (在庫: {{ $size->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <label class="block font-bold mb-2">数量</label>
                        <input type="number" name="quantity" value="1" min="1" class="w-20 border-gray-300 rounded">
                    </div>


                    <!-- カート追加ボタン -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product id" value="{{ $product->id }}">
                        <input type="hidden" name="type" value="{{ $product->item_type }}">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                            {{ $product->item_type === 'starter_kit' ? 'キットをまとめてカートに入れる' : 'カートに入れる' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- キットの中身を表示するセクション -->
            @if($product->item_type === 'starter_kit')
                <div class="mt-12 border-t pt-8">
                    <h2 class="text-2xl font-bold mb-6">セット内容（個別に追加・削除が可能です）</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($product->products as $subProduct)
                            <div class="flex border p-4 rounded-lg items-center bg-gray-50">
                                <!-- 子商品の画像 -->
                                <div class="w-20 h-20 flex-shrink-0 bg-gray-200 rounded overflow-hidden">
                                    @if($subProduct->productImages->isNotEmpty())
                                        <img src="{{ asset('storage/' . $subProduct->productImages->first()->image_url) }}"
                                            class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <!-- 子商品の情報 -->
                                <div class="ml-4">
                                    <h3 class="font-bold">{{ $subProduct->name }}</h3>
                                    <p class="text-sm text-gray-600">単品価格: ¥{{ number_format($subProduct->price) }}</p>
                                    <a href="{{ route('products.show', $subProduct->id) }}"
                                        class="text-blue-500 text-xs hover:underline">商品詳細へ</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>