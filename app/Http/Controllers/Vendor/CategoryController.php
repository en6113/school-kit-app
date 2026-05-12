<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * カテゴリー一覧を表示
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('created_at', 'desc')->get();

        return view('vendor.categories.index', compact('categories'));
    }

    /**
     * カテゴリー作成フォームを表示
     */
    public function create()
    {
        return view('vendor.categories.create');
    }

    /**
     * カテゴリーを新規作成
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('vendor.categories.index')
            ->with('success', 'カテゴリーを作成しました。');
    }

    /**
     * カテゴリー詳細を表示
     */
    public function show(Category $category)
    {
        $category->load('products');

        return view('vendor.categories.show', compact('category'));
    }

    /**
     * カテゴリー編集フォームを表示
     */
    public function edit(Category $category)
    {
        return view('vendor.categories.edit', compact('category'));
    }

    /**
     * カテゴリーを更新
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('vendor.categories.index')
            ->with('success', 'カテゴリーを更新しました。');
    }

    /**
     * カテゴリーを削除
     */
    public function destroy(Category $category)
    {
        // カテゴリーに紐づく商品がある場合は削除不可
        if ($category->products()->count() > 0) {
            return redirect()->route('vendor.categories.index')
                ->with('error', '商品が紐づいているカテゴリーは削除できません。');
        }

        $category->delete();

        return redirect()->route('vendor.categories.index')
            ->with('success', 'カテゴリーを削除しました。');
    }
}
