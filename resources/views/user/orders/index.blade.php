<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">注文履歴</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="py-3 px-6 text-left">注文ID</th>
                        <th class="py-3 px-6 text-left">合計金額</th>
                        <th class="py-3 px-6 text-left">ステータス</th>
                        <th class="py-3 px-6 text-left">注文日</th>
                        <th class="py-3 px-6 text-center">操作</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($orders as $order)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6">#{{ $order->id }}</td>
                            <td class="py-3 px-6 font-bold">¥{{ number_format($order->total_amount) }}</td>
                            <td class="py-3 px-6">
                                @if($order->status == 1)
                                    <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs">準備中</span>
                                @elseif($order->status == 0)
                                    <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs">キャンセル済み</span>
                                @else
                                    <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs">完了</span>
                                @endif
                            </td>
                            <td class="py-3 px-6">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="text-blue-500 hover:text-blue-700 underline">詳細を見る</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>