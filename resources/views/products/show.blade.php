<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>
        
    <div class="max-w-7xl mx-auto py-6 px-4">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="md:flex mt-4">
                <!-- 画像エリア -->
                <div class="md:w-1/2">
                    @if($product->productImages && $product->productImages->isNotEmpty())
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
                    <h1 class="text-3xl font-bold mt-2">{{ $product->name }}</h1>
                    <p class="text-2xl text-red-600 font-bold my-4">¥{{ number_format($product->price) }}</p>
                    {{-- 在庫表示 --}}
                    <div class="text-sm text-gray-500 mb-4">
                        @if($product->productSizes->count() > 1)
                            {{-- サイズ展開がある場合 --}}
                            <p class="text-sm text-gray-500 font-medium">
                                サイズごとの在庫数をご確認ください
                            </p>
                        @else
                            {{-- サイズ展開がない（通常の商品）場合 --}}
                            <p class="text-sm text-gray-500">
                                在庫: {{ $product->total_stock }}
                            </p>
                        @endif
                    </div>
                    {{-- 商品説明 --}}
                    <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                    {{-- フォーム（サイズ、数量選択） --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        {{-- サイズ選択（サイズがある場合のみ） --}}
                        @if(isset($product->productSizes) && $product->productSizes->isNotEmpty())
                            <div class="mb-4">
                                <label class="block font-bold mb-2">サイズ選択</label>
                                <select name="product_size_id" class="w-full border-gray-300 rounded">
                                    @foreach($product->productSizes as $productSize)
                                        <option value="{{ $productSize->id }}">{{ $productSize->size->size_name }} (在庫: {{ $productSize->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- 数量選択 --}}
                        <div class="mb-6">
                            <label class="block font-bold mb-2">数量</label>
                            <input type="number" name="quantity" value="1" min="1" class="w-20 border-gray-300 rounded">
                        </div>

                        {{-- カート追加ボタン(ユーザーのみ表示) --}}
                        <div class="mt-8 border-t pt-4">
                            @auth('web')
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <button type="submit"
                                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                                        カートに追加する
                                    </button>
                            @endauth
                        </div>
                    </form>
        
                <!-- 編集・削除リンク(登録した業者のみに表示) -->
                <div class="mt-8 border-t pt-4">
                    @auth('vendor')
                        @if($product->vendor_id === auth('vendor')->id())
                            <div class="flex gap-2">
                                {{-- 編集 --}}
                                <a href="{{ route('vendor.products.edit', $product->id) }}"
                                    class="text-sm text-blue-500 hover:underline">編集する</a>
                                {{-- 削除 --}}
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-200">削除する
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>