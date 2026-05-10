<x-vendor-layout>
    <x-slot name="title">商品一覧</x-slot>
    
    {{-- カテゴリー絞り込み中の場合に「解除」ボタンを表示 --}}
    @if(request('category'))
        <div class="mb-4">
            <a href="{{ route('products.index') }}"
                class="text-sm text-gray-600 bg-gray-200 px-3 py-1 rounded-full hover:bg-gray-300">
                ✕ カテゴリー絞り込みを解除
            </a>
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                <!-- 商品画像（最初の1枚を表示） -->
                <div class="h-48 bg-gray-200 flex items-center justify-center">

                    {{-- 画像表示のロジック --}}
                    @if($product->item_type === 'product' && isset($product->productImages) && $product->productImages->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->productImages->first()->image_url) }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400 text-sm">No Image</span>
                    @endif
                </div>

                <div class="p-4">
                    <!-- カテゴリー表示(商品の時のみ) -->
                    <div class="flex flex-wrap gap-1 mb-2">
                        @if($product->item_type === 'product')
                            @foreach($product->categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded hover:bg-blue-200 transition">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @else
                            <span class="bg-orange-100 text-orange-800 text-xs px-2 py-0.5 rounded">セット商品</span>
                        @endif
                    </div>

                    <h2 class="font-bold text-lg mb-1">{{ $product->name }}</h2>
                    <p class="text-red-600 font-semibold">¥{{ number_format($product->price) }}</p>

                    {{-- 在庫表示（商品の時のみ表示） --}}
                    @if($product->item_type === 'product')
                        <p class="text-sm text-gray-500 mb-4">在庫: {{ $product->stock }}</p>
                    @else
                        <p class="text-sm text-gray-500 mb-4">{{ $product->products->count() }}点の商品セット</p>
                    @endif

                    <div class="flex justify-between items-center">
                        {{-- 詳細リンク --}}
                        <a href="{{ route('products.show', $product->id) }}"
                            class="text-blue-500 hover:underline text-sm">詳細を見る</a>

                        {{-- 編集リンク(業者のみ) --}}
                        @auth('vendor')
                            @if($product->vendor_id === auth('vendor')->id())
                                <a href="{{ route('vendor.products.edit', $product->id) }}"
                                    class="bg-gray-100 px-3 py-1 rounded text-sm hover:bg-gray-200">編集</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-vendor-layout>