<x-app-layout>
    <x-slot name="title">スターターキット</x-slot>

    <h1 class="text-2xl font-bold mb-6">スターターキット一覧</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($starterKits as $kit)
            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition flex flex-col h-full bg-white">

                {{-- 基本情報 --}}
                <div class="p-4 flex-grow">
                    {{-- キットのイメージ画像、モデルにロジック（getImageUrl）を作成済 --}}
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        <img src="{{ $kit->getImageUrl() }}" class="w-full h-full object-cover">
                    </div>
                    <h2 class="font-bold text-lg mb-1">{{ $kit->name }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ $kit->description }}</p>
                    <p class="text-sm text-gray-500 mb-1">合計 {{ $kit->products_count }} 点の商品</p>
                    {{-- キットの合計金額、モデルにアクセサ（getTotalPriceAttribute）を作成済 --}}
                    <p class="text-red-600 font-semibold mb-4">¥{{ number_format($kit->total_price) }}</p>
                    <a href="{{ route('starterKits.show', $kit->id) }}" class="text-blue-500 hover:underline text-sm">キットに含まれる商品を見る</a>
                </div>


                {{-- 編集・削除リンク(作成した業者のみ) --}}
                @auth('vendor')
                    @if($kit->vendor_id === auth('vendor')->id())
                        <div class="" flex gap-2">
                            <a href="{{ route('vendor.starterKits.edit', $kit->id) }}"
                                class="bg-gray-100 px-3 py-1 rounded text-sm hover:bg-gray-200">編集</a>
                            <form action="{{ route('vendor.starterKits.destroy', $kit->id) }}" method="POST"
                                onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-200">削除</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-gray-500">
                登録されているスターターキットがありません。
            </div>
        @endforelse
    </div>
</x-app-layout>