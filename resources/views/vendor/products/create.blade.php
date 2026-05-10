<form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data"
    class="max-w-2xl bg-white p-6 rounded-lg shadow">
    @csrf
    <h2 class="text-xl font-bold mb-6 border-b pb-2">新規商品登録</h2>

    <div class="mb-4">
        <label class="block font-bold mb-1">カテゴリー（複数選択可）</label>
        <div class="grid grid-cols-3 gap-2 border p-3 rounded">
            @foreach($categories as $category)
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="mr-2">
                    {{ $category->name }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block font-bold mb-1">商品名</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded" required>
        </div>
        <div>
            <label class="block font-bold mb-1">価格</label>
            <input type="number" name="price" class="w-full border-gray-300 rounded" required>
        </div>
    </div>

    <div class="mb-4">
        <label class="block font-bold mb-1">在庫数（基本）</label>
        <input type="number" name="stock" class="w-full border-gray-300 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block font-bold mb-1">商品説明</label>
        <textarea name="description" rows="4" class="w-full border-gray-300 rounded"></textarea>
    </div>

    <div class="mb-6">
        <label class="block font-bold mb-1">商品画像</label>
        <input type="file" name="images[]" multiple class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-1">※複数選択可能です</p>
    </div>

    <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded hover:bg-green-700">
        商品を登録する
    </button>
</form>