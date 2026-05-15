<x-app-layout>
    <x-slot name="title">スクールキット作成</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">新規スクールキット作成</h1>

        <form action="{{ route('admin.schoolKits.store') }}" method="POST">
            @csrf

            {{-- キット名 --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">キット名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- キットの説明 --}}
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
            </div>
            
            {{-- イメージ画像 --}}
            <div class="mb-6">
                <label for="images" class="block text-gray-700 font-medium mb-2">イメージ画像</label>
                <input type="file" id="images" name="images[]" multiple class="w-full text-sm">
            </div>
            
            {{-- 商品 --}}
            <div class="mb-6">
                <label for="products"
                    class="block text-gray-700 font-medium mb-2">商品（複数選択可）</label>
                <div class="grid grid-cols-3 gap-2 border p-3 rounded">
                    @foreach($products as $product)
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="product_id[]" value="{{ $product->id }}" class="mr-2">
                            {{ $product->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- ボタン --}}
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    作成
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</x-app-layout>