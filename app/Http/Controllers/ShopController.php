<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Homepage: List all articles
    public function articlesHome()
    {
        $articles = \App\Models\Article::with('category')->latest()->get();
        return view('shop.home', compact('articles'));
    }

    // Checkout page for an article
    public function checkout($article)
    {
        $article = \App\Models\Article::with('category')->findOrFail($article);
        return view('shop.checkout', compact('article'));
    }

    // Order submission (POST)
    public function orderSubmit(Request $request, $article)
    {
        $article = \App\Models\Article::findOrFail($article);
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
        ]);
        $validated['article_id'] = $article->id;
        \App\Models\Command::create($validated);
        return redirect()->route('shop.checkout', $article->id)
            ->with('success', 'Your order has been submitted!');
    }
}
