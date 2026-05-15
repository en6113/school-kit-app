<x-app-layout>
    <x-slot name="title">スターターキット</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($starterKits as $kit)
            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">

                    <h2 class="font-bold text-lg mb-1">{{ $kit->name }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ $kit->description }}</p>
                    <p class="text-sm text-gray-500 mb-4">
                        セット内容：合計 {{ $kit->products_count }} 点の商品
                    </p>
                    <p class="text-red-600 font-semibold">¥{{ number_format($kit->total_price) }}</p>

                    <div class="flex justify-between items-center">
                        {{-- 詳細リンク --}}
                        <a href="{{ route('starterKits.show', $kit->id) }}"
                            class="text-blue-500 hover:underline text-sm">キットの詳細を見る</a>

                    {{-- カート追加ボタン(ユーザーのみ表示) --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <div class="mt-8 border-t pt-4">
                            @auth('web')
                                <input type="hidden" name="starter_kit_id" value="{{ $kit->id }}">

                                <button type="submit"
                                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                                    カートに追加する※注文前にサイズや数量の変更及び一部削除が可能です。
                                </button>
                            @endauth
                        </div>
                    </form>

                        {{-- 編集・削除リンク(作成した業者のみ) --}}
                        @auth('vendor')
                            @if($kit->vendor_id === auth('vendor')->id())
                                <div class="flex gap-2">
                                    {{-- 編集 --}}
                                    <a href="{{ route('vendor.starterKits.edit', $kit->id) }}"
                                        class="bg-gray-100 px-3 py-1 rounded text-sm hover:bg-gray-200">編集</a>
                                    {{-- 削除 --}}
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
                </div>
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-gray-500">
                登録されているスターターキットがありません。
            </div>
        @endforelse
    </div>
</x-app-layout>