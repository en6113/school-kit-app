<x-app-layout>
    <x-slot name="title">スクールキット編集</x-slot>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">スクールキット編集</h1>

        <form action="{{ route('admin.schoolKits.update', $schoolKit) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- キット名 --}}
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">キット名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $schoolKit->name) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- キットの説明 --}}
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">説明</label>
                <input type="textarea" name="description" id="description" value="{{ old('description', $schoolKit->description) }}" rows="4"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>
            
            {{-- イメージ画像 --}}
            <div class="mb-6">
                <label for="images" class="block text-gray-700 font-medium mb-2">イメージ画像</label>
                <input type="file" id="images" name="images[]" multiple class="w-full text-sm">
            </div>
            
            {{-- 商品 --}}
            <div class="mb-6">
                <label for="products" class="block text-gray-700 font-medium mb-2">商品（複数選択可）</label>
                <div class="grid grid-cols-3 gap-2 border p-3 rounded">
                    @foreach($products as $product)
                        <label class="flex items-center text-sm">
                            <input type="checkbox" name="product_id[]" value="{{ $product->id }}" class="mr-2"
                                @checked(
                                    (is_array(old('product_id')) && in_array($product->id, old('product_id'))) ||
                                    (!old('product_id') && $schoolKit->products->contains($product->id))
                                )
                            >
                            {{ $product->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- ボタン --}}
            <div class="flex space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    更新
                </button>
                <a href="{{ route('schoolKits.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</x-app-layout>