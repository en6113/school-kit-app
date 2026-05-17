<x-app-layout>
    <x-slot name="title">商品一覧</x-slot>

    <h1 class="text-2xl font-bold mb-6">商品一覧</h1>

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
        @forelse($products as $product)
            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                {{-- 商品画像（最初の1枚を表示） --}}
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    @if(isset($product->productImages) && $product->productImages->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->productImages->first()->image_url) }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400 text-sm">No Image</span>
                    @endif
                </div>

                <div class="p-4">
                    {{-- カテゴリー --}}
                    <div class="flex flex-wrap gap-1 mb-2">
                        @if(isset($product->categories) && count($product->categories) > 0)
                            @foreach($product->categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                    class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded hover:bg-blue-200 transition">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- 基本情報 --}}
                    <h2 class="font-bold text-lg mb-1">{{ $product->name }}</h2>
                    <p class="text-red-600 font-semibold">¥{{ number_format($product->price) }}</p>

                    {{-- 在庫 --}}
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

                    <div class="flex justify-between items-center">
                        {{-- 詳細リンク --}}
                        <a href="{{ route('products.show', $product->id) }}"
                            class="text-blue-500 hover:underline text-sm">詳細を見る</a>

                        {{-- 編集・削除リンク(業者のみ) --}}
                        <div class="" flex gap-2">
                            {{-- 編集 --}}
                            @can('update', $product)
                                <a href="{{ route('vendor.products.edit', $product->id) }}"
                                    class="text-sm text-blue-500 hover:underline">
                                    編集
                                </a>
                            @endcan
                            {{-- 削除 --}}
                            @can('delete', $product)
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-200">
                                        削除
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-gray-500">
                登録されている商品がありません。
            </div>
        @endforelse
    </div>
</x-app-layout>