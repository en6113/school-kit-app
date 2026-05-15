<x-app-layout>
    <x-slot name="title">{{ $schoolKitItem->name }}</x-slot>

    <h1 class="text-2xl font-bold mb-4">{{ $schoolKitItem->name }}の内容物</h1>

    <form action="{{ route('cart.add_kit') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            @forelse($schoolKitItem->products as $product)
                @php $index = $loop->index; @endphp

                <div class="md:flex mt-4">
                    {{-- 商品画像 --}}
                    <div class="md:w-1/2">
                        @if($product->productImages && $product->productImages->isNotEmpty())
                            <img src="{{ asset('storage/' . $product->productImages->first()->image_url) }}" class="w-full rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                    </div>

                    {{-- 基本情報 --}}
                    <div class="md:w-1/2 md:ml-6 mt-4 md:mt-0">
                        <h2 class="text-3xl font-bold mt-2">{{ $product->name }}</h2>
                        <p class="text-2xl text-red-600 font-bold my-4">¥{{ number_format($product->price) }}</p>
                        <div class="text-sm text-gray-500 mb-4">
                            @if($product->productSizes->count() > 1)
                                <p class="text-sm text-gray-500 font-medium">
                                    サイズごとの在庫数をご確認ください
                                </p>
                            @else
                                <p class="text-sm text-gray-500">
                                    在庫: {{ $product->total_stock }}
                                </p>
                            @endif
                        </div>
                        <p class="text-gray-700 mb-6">{{ $product->description }}</p>

                        {{-- 商品IDを配列形式で送るための隠しフィールド --}}
                        <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product->id }}">

                        {{-- サイズ選択（サイズがある場合のみ） --}}
                        @if($product->productSizes && $product->productSizes->isNotEmpty())
                            <div class="mb-4">
                                <label class="block font-bold mb-2">サイズ選択</label>
                                <select name="products[{{ $index }}][product_size_id]" class="w-full border-gray-300 rounded">
                                    @foreach($product->productSizes as $productSize)
                                        <option value="{{ $productSize->id }}">{{ $productSize->size->size_name }} (在庫:
                                            {{ $productSize->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- 数量選択 --}}
                        <div class="mb-6">
                            <label class="block font-bold mb-1 text-sm">数量</label>
                            <input type="number" name="products[{{ $index }}][quantity]" value="1" min="1" class="w-20 border-gray-300 rounded">
                        </div>
                    </div>
                </div>
            @empty
                <p>このキットには商品が含まれていません。</p>
            @endforelse
        </div>

        {{-- カート追加ボタン(ユーザーのみ表示) --}}
        @auth('web')
            <div class="mt-8 p-6 bg-gray-100 rounded-lg text-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-lg shadow-lg">
                    以上の商品をすべてまとめてカートに追加する
                </button>
            </div>
        @endauth
    </form>
</x-app-layout>