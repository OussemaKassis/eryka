<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $articles = \App\Models\Article::with('category', 'images')
            ->where('category_id', $category->id)
            ->orderBy('sort_order')
            ->get();

        return view('shop.category', compact('category', 'articles'));
    }
}
