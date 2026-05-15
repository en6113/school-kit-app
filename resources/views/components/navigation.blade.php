<nav class="bg-gray-800 shadow-lg">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="flex justify-between items-center py-4">

            {{-- 【共通部分】ロゴ --}}
            <a href="{{ route('products.index') }}" class="text-white text-xl font-bold hover:text-gray-300">
                📋 スクールキットアプリ
            </a>

            {{-- 権限による切り替え --}}
            @if(auth('vendor')->check() || request()->is('vendor*'))

                {{-- 【Vendor用ナビ】 --}}
                <div class="flex items-center space-x-6 ml-6 mr-auto">
                    @auth('vendor')
                        <a href="{{ route('starterKits.index') }}"
                        class="text-gray-300 hover:text-white text-sm font-medium">キット一覧</a>
                        <a href="{{ route('vendor.products.create') }}"
                            class="text-gray-300 hover:text-white text-sm font-medium">＋商品登録</a>
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @auth('vendor')
                        <span class="text-gray-300 text-sm">{{ auth('vendor')->user()->name }}さん</span>
                        <form method="POST" action="{{ route('vendor.logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-300 hover:text-white text-sm">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('vendor.login') }}" class="text-gray-300 hover:text-white text-sm">ログイン</a>
                        <a href="{{ route('vendor.register') }}" class="text-gray-300 hover:text-white text-sm">新規登録</a>
                    @endauth
                </div>

            @else

                {{-- 【User用ナビ】 --}}
                <div class="flex items-center space-x-6 ml-6 mr-auto">
                    @auth('web')
                        <a href="{{ route('starterKits.index') }}" class="text-gray-300 hover:text-white text-sm font-medium">キット一覧</a>
                        <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white text-sm font-medium">買い物かご</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-300 hover:text-white text-sm font-medium">注文履歴</a>
                        @can('admin')
                            <a href="{{ route('admin.starterKits.create') }}" class="text-gray-300 hover:text-white text-sm font-medium">＋スクールキット登録</a>
                            <a href="{{ route('admin.categories.index') }}" class="text-gray-300 hover:text-white text-sm font-medium">＋カテゴリー登録</a>
                        @endcan
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @auth('web')
                        <span class="text-gray-300 text-sm">{{ auth('web')->user()->name }}さん</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-300 hover:text-white text-sm">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm">ログイン</a>
                        <a href="{{ route('register') }}" class="text-gray-300 hover:text-white text-sm">新規登録</a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</nav>