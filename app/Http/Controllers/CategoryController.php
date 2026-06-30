<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $category->load('parent', 'children');

        $articles = \App\Models\Article::with('category', 'images')
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return view('shop.category', compact('category', 'articles'));
    }
}
