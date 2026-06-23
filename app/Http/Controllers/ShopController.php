<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Command;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Homepage: featured articles + marketing sections
    public function articlesHome()
    {
        $articles = Article::with('category')->latest()->take(8)->get();
        return view('shop.home', compact('articles'));
    }

    // All products, optionally filtered by category (and its sub-categories)
    public function products(Request $request)
    {
        $query = Article::with('category')->latest();
        $activeCategory = null;

        if ($categoryId = $request->query('category')) {
            $activeCategory = Category::with('children')->findOrFail($categoryId);

            $categoryIds = $activeCategory->children->isNotEmpty()
                ? $activeCategory->children->pluck('id')->push($activeCategory->id)
                : [$activeCategory->id];

            $query->whereIn('category_id', $categoryIds);
        }

        $articles = $query->get();
        $familyCategories = Category::topLevel()->with('children')->orderBy('title')->get();

        return view('shop.products', compact('articles', 'familyCategories', 'activeCategory'));
    }

    // Product detail page
    public function show($article)
    {
        $article = Article::with('category')->findOrFail($article);
        return view('shop.product', compact('article'));
    }

    // Checkout page for an article
    public function checkout($article)
    {
        $article = Article::with('category')->findOrFail($article);
        return view('shop.checkout', compact('article'));
    }

    // Order submission (POST)
    public function orderSubmit(Request $request, $article)
    {
        $article = Article::findOrFail($article);
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max($article->quantity, 0)],
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
        ], [
            'quantity.max' => $article->quantity > 0
                ? "Only {$article->quantity} left in stock."
                : 'This product is out of stock.',
        ]);
        $validated['article_id'] = $article->id;
        Command::create($validated);
        $article->decrement('quantity', $validated['quantity']);

        return redirect()->route('shop.checkout', $article->id)
            ->with('success', 'Your order has been submitted!');
    }

    public function about()
    {
        return view('shop.about');
    }

    public function contact()
    {
        return view('shop.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);
        ContactMessage::create($validated);
        return redirect()->route('shop.contact')
            ->with('success', 'Thanks for reaching out! We will get back to you soon.');
    }
}
