<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Vendor\ProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Size;
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
        $productQuery = Product::query();

        // カテゴリーIDが送られてきた場合、そのカテゴリーを持つ商品のみに絞り込む
        if ($request->filled('category')) {
            $productQuery->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $products = $productQuery->with(['categories', 'productImages', 'productSizes'])->orderBy('created_at', 'desc')->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $sizes = Size::all();
        $starterKits = StarterKit::all();

        return view('vendor.products.create', compact('categories', 'sizes', 'starterKits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $validated['vendor_id'] = auth()->id();

        $product = Product::create($validated);

        //サイズ展開の保存（中間テーブルに保存する）
        if ($request->has('product_sizes')) {
            $product->sizes()->attach($request->product_sizes);
        }

        //カテゴリーの保存（中間テーブルに保存する）
        if ($request->has('category_id')){
            $product->categories()->attach($request->category_id);
        }

        return redirect()->route('products.index')->with('success', '商品を作成しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['categories', 'productImages', 'productSizes.size'])->find($id);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('categories','sizes')->findOrFail($id);
        $categories = Category::all();
        $starterKits = StarterKit::all();

        //モデルのgetSizeTypeAttributeアクセサを使用
        $type = $product->size_type;
        $sizeOptions = $type
            ? Size::where('type', $type)->get()
            : collect();

        $stocks = $product->productSizes->pluck('stock', 'size_id')->toArray();

        return view('vendor.products.edit', compact('product','categories','starterKits','sizeOptions','stocks',));
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

        // カテゴリーの更新（中間テーブルに保存）
        if ($request->has('category_id')) {
            $product->categories()->sync($request->category_id);
        }

        // サイズ別在庫の更新（中間テーブルに保存）在庫が入力されている（もしくは0）の場合のみ同期対象にする
        if ($request->has('sizes')) {
            $syncData = [];
            foreach ($request->sizes as $sizeId => $stock) {
                if (!is_null($stock)) {
                    $syncData[$sizeId] = ['stock' => $stock];
                }
            }
            $product->sizes()->sync($syncData);
        }

        return redirect()->route('products.index')->with('success', '商品を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if($product->vendor_id !== auth()->id()) {
            return redirect()->route('products.index')->with('error', 'この商品を削除する権限がありません。');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
}
