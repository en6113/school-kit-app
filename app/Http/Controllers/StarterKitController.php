<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StarterKit;
use App\Models\Product;

class StarterKitController extends Controller
{
    public function index(Request $request)
    {
        $starterKits = StarterKit::with('products')->withCount('products')->get();

        return view('starterKits.index', compact('starterKits'));
    }

    public function create()
    {
        $products = Product::with('product_sizes')->get();

        return view('vendor.starterKits.create', compact('products'));
    }

    public function store(StarterKitRequest $request)
    {
        $validated = $request->validated();
        $validated['vendor_id'] = auth()->id();

        $starterKit = StarterKit::create($validated);

        //キットに商品を保存（中間テーブルに保存する）
        if ($request->has('product_id')) {
            $starterKit->products()->attach($request->product_id);
        }

        return redirect()->route('starterKits.index')->with('success', 'スターターキットを作成しました。');
    }


    public function show(string $id)
    {
        $starterKitItem = StarterKit::with([
            'products.productImages',
            'products.categories',
            'products.productSizes'
        ])->findOrFail($id);

        return view('starterKits.show', compact('starterKitItem'));
    }

    public function edit(string $id)
    {
        $starterKit = StarterKit::with('products')->findOrFail($id);

        return view('vendor.starterKits.edit', compact('starterKit'));
    }

    public function update(StarterKitRequest $request, string $id)
    {
        $starterKit = StarterKit::findOrFail($id);
        $validated = $request->validated();
        $validated['vendor_id'] = auth()->id();

        $starterKit->update($validated);

        // キット内商品の更新（中間テーブルに保存）
        if ($request->has('product_id')) {
            $starterKit->products()->sync($request->product_id);
        }

        return redirect()->route('starterKits.index')->with('success', 'スターターキットを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $starterKit = StarterKit::findOrFail($id);

        if ($starterKit->vendor_id !== auth()->id()) {
            return redirect()->route('starterKits.index')->with('error', 'このスターターキットを削除する権限がありません。');
        }

        $starterKit->delete();
        return redirect()->route('starterKits.index')->with('success', 'スターターキットを削除しました。');
    }
}
