<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\StarterKit;


class ProductController extends Controller
{
    //作成・編集は業者のみが行えるようにするため、コンストラクタでミドルウェアを設定
    public function __construct()
    {
        // create, store, edit, update, destroy は 'auth:vendor' 門番を通った人だけ許可
        $this->middleware('auth:vendor')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // クエリのベースを作成
        $productQuery = Product::query();

        // カテゴリーIDが送られてきた場合、そのカテゴリーを持つ商品のみに絞り込む
        if ($request->filled('category')) {
            $productQuery->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $products = $productQuery->with(['categories', 'productImages', 'productSizes'])->get();
        $products->each(fn($p) => $p->item_type = 'product');

        //スターターキットクエリ
        $kits = StarterKit::with(['products'])->get();
        $kits->each(fn($k) => $k->item_type = 'starter_kit');

        //両方を結合して、アイテムの種類でソート
        $allProducts = $products->concat($kits)->sortBy('created_at');

        return view('products.index', ['products' => $allProducts]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 1. 商品を探す
        $product = Product::with(['categories', 'productImages', 'productSizes'])->find($id);

        if ($product) {
            $product->item_type = 'product';
        } else {
            // 2. 商品で見つからなければスターターキットを探す
            $product = StarterKit::with('products.productImages')
                ->withCount('products')
                ->findOrFail($id);

            $product->item_type = 'starter_kit';
        }

        // 変数名を $product に統一してビューに渡す（既存のビューとの互換性のため）
        return view('products.show', ['product' => $product]);
    }
}
