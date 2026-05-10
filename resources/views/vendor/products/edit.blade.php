<form action="{{ route('vendor.products.update', $product->id) }}" method="POST"
    class="max-w-2xl bg-white p-6 rounded-lg shadow">
    @csrf
    @method('PUT')

    <h2 class="text-xl font-bold mb-6 border-b pb-2">商品情報の編集</h2>

    {{-- 基本情報はCreateと同様のため省略（value設定を忘れずに） --}}
    <div class="mb-4">
        <label class="block font-bold mb-1">商品名</label>
        <input type="text" name="name" value="{{ $product->name }}" class="w-full border-gray-300 rounded">
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

    <div class="flex gap-4">
        <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">
            更新する
        </button>
        <a href="{{ route('vendor.dashboard') }}" class="px-6 py-2 border rounded hover:bg-gray-50">キャンセル</a>
    </div>
</form>