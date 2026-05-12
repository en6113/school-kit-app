<x-vendor-layout>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-slot name="title">商品登録</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">新規商品登録</h1>

        <form action="{{ route('vendor.products.store') }}" method="POST">
            @csrf
            
            {{-- 商品名 --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">商品名</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                </div>

            {{-- 価格 --}}
            <div class="mb-4">
                <label for="price" class="block text-gray-700 font-medium mb-2">価格</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 在庫数 --}}
            <div class="mb-4">
                <label for="total_stock" class="block text-gray-700 font-medium mb-2">在庫数（基本）※サイズ展開がある場合は０とご記入ください。</label>
                <input type="number" id="total_stock" name="total_stock" value="{{ old('total_stock') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- 商品説明 --}}
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">商品説明</label>
                <textarea id="description" name="description" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
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
                <label for="categories" class="block text-gray-700 font-medium mb-2">カテゴリー（複数選択可）※サイズ展開がある場合は「衣服」か「履物」を必ず選択してください。</label>
                <div class="grid grid-cols-3 gap-2 border p-3 rounded">
                    @foreach($categories as $category)
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="mr-2">
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- 保存ボタン --}}
            <div class="flex space-x-4">
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded hover:bg-green-700">
                    商品を登録する
                </button>
                <a href="{{ route('vendor.products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</x-vendor-layout>