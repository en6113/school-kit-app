<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * カテゴリー一覧を表示
     */
    public function index()
    {
        $categories = Category::withCount('items')->orderBy('created_at', 'desc')->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * カテゴリー詳細を表示
     */
    public function show(Category $category)
    {
        $category->load('items');

        return view('categories.show', compact('category'));
    }
}
