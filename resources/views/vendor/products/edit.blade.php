<x-vendor-layout>
    <x-slot name="title">商品編集</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">商品情報の編集</h1>

        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 商品名 --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">商品名</label>
                <input type="text" id="name" name="name" value="{{ $product->name }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 価格 --}}
            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-medium mb-2">価格</label>
                <input type="number" id="price" name="price" value="{{ $product->price }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 在庫数 --}}
            <div class="mb-4">
                <label for="total_stock" class="block text-gray-700 font-medium mb-2">在庫数（基本）</label>
                <input type="number" id="total_stock" name="total_stock" value="{{ $product->total_stock }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                @error('total_stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 商品説明 --}}
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">商品説明</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ $product->description }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 商品画像 --}}
            <div class="mb-6">
                <label for="images" class="block text-gray-700 font-medium mb-2">商品画像</label>
                <input type="file" id="images" name="images[]" multiple class="w-full text-sm">
                <p class="text-xs text-gray-500 mt-1">※複数選択可能です</p>
                @error('images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- カテゴリー --}}
            <div class="mb-6">
                <label for="categories" class="block text-gray-700 font-medium mb-2">カテゴリー（複数選択可）</label>
                <div class="grid grid-cols-3 gap-2 border p-3 rounded">
                    @foreach($categories as $category)
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="mr-2" {{ $product->categories->contains($category) ? 'checked' : '' }}>
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- サイズ別在庫管理 --}}
            <div class="mb-6 p-4 bg-gray-50 rounded border">
                <h3 class="font-bold mb-3 text-sm text-gray-600">サイズ別在庫設定</h3>

                <div class="space-y-3">
                    @if($sizeOptions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($sizeOptions as $option)
                                <div class="flex items-center justify-between p-2 bg-white border rounded">
                                    <span class="font-medium text-gray-700">{{ $option->size_name }}</span>

                                    <div class="flex items-center">
                                        <label class="mr-2 text-xs text-gray-500">在庫数:</label>
                                        <input type="number" name="sizes[{{ $option->id }}]" value="{{ old("sizes.{$option->id}", $stocks[$option->id] ?? 0) }}" placeholder="0" class="border-gray-300 rounded shadow-sm w-20 text-right">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-red-500 text-sm">この商品にサイズ設定はありません。※サイズ設定が必要な場合はカテゴリーで「衣服」または「履物」を選択してください。</p>
                    @endif
                </div>
            </div>

            {{-- 更新ボタン --}}
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    更新する
                </button>
                <a href="{{ route('products.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</x-vendor-layout>