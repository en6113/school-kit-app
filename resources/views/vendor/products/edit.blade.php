<x-vendor-layout>
    <x-slot name="title">商品編集</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">商品情報の編集</h1>

        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 基本情報はCreateと同様のため省略（value設定必要） --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">商品名</label>
                <input type="text" id="name" name="name" value="{{ $product->name }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- サイズ別在庫管理（Product_sizesテーブル用） --}}
            <div class="mb-6 p-4 bg-gray-50 rounded border">
                <h3 class="font-bold mb-3 text-sm text-gray-600">サイズ別在庫設定（サイズ展開がある場合）</h3>
                <div class="space-y-2">
                    @foreach($product->sizes as $size)
                        <div class="flex items-center gap-4">
                            <span class="w-16 font-medium">{{ $size->size }}cm</span>
                            <input type="number" name="sizes[{{ $size->id }}]" value="{{ $size->stock }}"
                                class="w-24 border-gray-300 rounded text-sm">
                            <span class="text-xs text-gray-400 font-normal">現在の在庫</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 更新ボタン --}}
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    更新する
                </button>
                <a href="{{ route('vendor.products.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</x-vendor-layout>