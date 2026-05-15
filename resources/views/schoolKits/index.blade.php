<x-app-layout>
    <x-slot name="title">スクールキット</x-slot>

    <h1 class="text-2xl font-bold mb-6">スクールキット一覧</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($schoolKits as $kit)
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
                    <a href="{{ route('schoolKits.show', $kit->id) }}" class="text-blue-500 hover:underline text-sm">キットに含まれる商品を見る</a>
                </div>


                {{-- 編集・削除リンク(管理者のみ) --}}
                @auth('web')
                    @can('admin')
                        <div class="" flex gap-2">
                            <a href="{{ route('admin.schoolKits.edit', $kit->id) }}"
                                class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">編集</a>
                            <form action="{{ route('admin.schoolKits.destroy', $kit->id) }}" method="POST"
                                onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="m-2 bg-red-500 hover:bg-gray-200 text-white px-3 py-1 rounded text-sm">削除</button>
                            </form>
                        </div>
                    @endcan
                @endauth
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-gray-500">
                登録されているスクールキットがありません。
            </div>
        @endforelse
    </div>
</x-app-layout>