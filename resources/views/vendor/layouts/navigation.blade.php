<nav class="bg-gray-800 shadow-lg">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="flex justify-between items-center py-4">
            {{-- ロゴ --}}
            <a href="{{ route('vendor.products.index') }}" class="text-white text-xl font-bold hover:text-gray-300">
                📋 スクールキットアプリ
            </a>

            {{-- ナビゲーションリンク（商品登録・カテゴリー登録） --}}
            <div class="flex items-center space-x-6 ml-6 mr-auto">
                <a href="{{ route('vendor.products.create') }}" class="text-gray-300 hover:text-white text-sm font-medium">
                    ＋ 商品登録
                </a>
                <a href="{{ route('vendor.categories.index') }}" class="text-gray-300 hover:text-white text-sm font-medium">
                    ＋ カテゴリー登録
                </a>
            </div>

            {{-- ナビゲーションリンク（認証関連） --}}
            <div class="flex items-center space-x-4">
                @auth('vendor')
                    <span class="text-gray-300">
                        {{ auth('vendor')->user()->name }}さん
                    </span>
                    <form method="POST" action="{{ route('vendor.logout') }}">
                        @csrf
                        <x-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            ログアウト
                        </x-nav-link>
                    </form>
                @else
                    <a href="{{ route('vendor.login') }}" class="text-gray-300 hover:text-white">
                        ログイン
                    </a>
                    <a href="{{ route('vendor.register') }}" class="text-gray-300 hover:text-white">
                        新規登録
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>