<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolKit;
use App\Models\Product;
use App\Http\Requests\SchoolKitRequest;

class SchoolKitController extends Controller
{
    public function index(Request $request)
    {
        $schoolKits = SchoolKit::with('products')->withCount('products')->get();

        return view('schoolKits.index', compact('schoolKits'));
    }

    public function create()
    {
        $products = Product::all();

        return view('admin.schoolKits.create',compact('products'));
    }

    public function store(SchoolKitRequest $request)
    {
        $validated = $request->validated();
        $schoolKit = SchoolKit::create($validated);

        //キットに商品を保存（中間テーブルに保存する）
        if ($request->has('product_id')) {
            $schoolKit->products()->attach($request->product_id);
        }

        return redirect()->route('schoolKits.index')->with('success', 'スターターキットを作成しました。');
    }


    public function show(string $id)
    {
        $schoolKitItem = SchoolKit::with([
            'products.productImages',
            'products.categories',
            'products.productSizes'
        ])->findOrFail($id);

        return view('schoolKits.show', compact('schoolKitItem'));
    }

    public function edit(string $id)
    {
        $schoolKit = SchoolKit::with('products')->findOrFail($id);
        $products = Product::all();

        return view('admin.schoolKits.edit', compact('schoolKit','products'));
    }

    public function update(SchoolKitRequest $request, string $id)
    {
        $schoolKit = SchoolKit::findOrFail($id);
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $schoolKit->update($validated);

        // キット内商品の更新（中間テーブルに保存）
        if ($request->has('product_id')) {
            $schoolKit->products()->sync($request->product_id);
        }

        return redirect()->route('schoolKits.index')->with('success', 'スターターキットを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schoolKit = SchoolKit::findOrFail($id);
        $schoolKit->delete();

        return redirect()->route('schoolKits.index')->with('success', 'スクールキットを削除しました。');
    }
}
