<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Vendor\ProductRequest;
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

        return view('vendor.products.index', ['products' => $allProducts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $productSizes = ProductSize::all();
        $starterKits = StarterKit::all();

        return view('vendor.products.create', compact('categories', 'productSizes', 'starterKits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $validated['vendor_id'] = auth()->id();

        Product::create($validated);

        return redirect()->route('vendor.products.index')->with('success', '商品を作成しました。');
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
        return view('vendor.products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $productSizes = ProductSize::all();
        $starterKits = StarterKit::all();

        return view('vendor.products.edit', compact('product', 'categories', 'productSizes', 'starterKits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();
        $validated['vendor_id'] = auth()->id();

        $product->update($validated);

        return redirect()->route('vendor.products.index')->with('success', '商品を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if($product->vendor_id !== auth()->id()) {
            return redirect()->route('vendor.products.index')->with('error', 'この商品を削除する権限がありません。');
        }

        $product->delete();
        return redirect()->route('vendor.products.index')->with('success', '商品を削除しました。');
    }
}
