<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $articles = Article::with('category')->whereIn('id', array_keys($cart))->get();

        $items = $articles->map(function (Article $article) use ($cart) {
            $quantity = $cart[$article->id];
            return [
                'article' => $article,
                'quantity' => $quantity,
                'subtotal' => $article->price * $quantity,
            ];
        });

        $total = $items->sum('subtotal');

        return view('shop.cart', compact('items', 'total'));
    }

    public function add(Request $request, $article)
    {
        $article = Article::findOrFail($article);

        if ($article->quantity <= 0) {
            return back()->with('error', "{$article->title} is out of stock.");
        }

        $cart = session('cart', []);
        $requested = max(1, (int) $request->input('quantity', 1));
        $newQuantity = min($article->quantity, ($cart[$article->id] ?? 0) + $requested);
        $cart[$article->id] = $newQuantity;
        session(['cart' => $cart]);

        return back()->with('success', "{$article->title} added to your cart.");
    }

    public function update(Request $request, $article)
    {
        $article = Article::findOrFail($article);
        $quantity = (int) $request->input('quantity', 1);

        $cart = session('cart', []);

        if ($quantity <= 0) {
            unset($cart[$article->id]);
        } else {
            $cart[$article->id] = min($quantity, $article->quantity);
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove($article)
    {
        $article = Article::findOrFail($article);

        $cart = session('cart', []);
        unset($cart[$article->id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $articles = Article::with('category')->whereIn('id', array_keys($cart))->get();

        $items = $articles->map(function (Article $article) use ($cart) {
            $quantity = $cart[$article->id];
            return [
                'article' => $article,
                'quantity' => $quantity,
                'subtotal' => $article->price * $quantity,
            ];
        });

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $total = $items->sum('subtotal');

        return view('shop.cart-checkout', compact('items', 'total'));
    }

    public function checkoutSubmit(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $validated = $request->validate([
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
        ]);

        $articles = Article::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $articleId => $quantity) {
            $article = $articles->get($articleId);
            if (!$article || $article->quantity < $quantity) {
                return redirect()->route('cart.index')
                    ->with('error', ($article->title ?? 'An item').' no longer has enough stock. Please update your cart.');
            }
        }

        $groupId = (string) Str::uuid();

        foreach ($cart as $articleId => $quantity) {
            Command::create($validated + [
                'group_id' => $groupId,
                'article_id' => $articleId,
                'quantity' => $quantity,
            ]);
            $articles->get($articleId)->decrement('quantity', $quantity);
        }

        session()->forget('cart');

        return redirect()->route('shop.home')
            ->with('success', 'Your order has been placed! Thank you for shopping with us.');
    }
}
