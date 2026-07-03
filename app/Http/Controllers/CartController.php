<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = $this->buildCartItems($cart);
        $subtotal = $items->sum('subtotal');
        $shipping = $items->isEmpty() ? 0 : Command::SHIPPING_FEE;
        $total = $subtotal + $shipping;

        return view('shop.cart', compact('items', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request, $article)
    {
        $article = Article::findOrFail($article);

        if ($article->quantity <= 0) {
            return back()->with('error', __('site.flash_out_of_stock', ['title' => $article->title]));
        }

        $color = $request->input('color') ?: null;
        $key = $this->cartKey($article->id, $color);

        $cart = session('cart', []);
        $requested = max(1, (int) $request->input('quantity', 1));
        $existingQuantity = $cart[$key]['quantity'] ?? 0;

        $cart[$key] = [
            'article_id' => $article->id,
            'color' => $color,
            'quantity' => min($article->quantity, $existingQuantity + $requested),
        ];

        session(['cart' => $cart]);

        return back()->with('success', __('site.flash_added_to_cart', ['title' => $article->title]));
    }

    public function update(Request $request, $key)
    {
        $cart = session('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index');
        }

        $article = Article::findOrFail($cart[$key]['article_id']);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = min($quantity, $article->quantity);
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', __('site.flash_cart_updated'));
    }

    public function remove($key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', __('site.flash_item_removed'));
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $items = $this->buildCartItems($cart);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $items->sum('subtotal');
        $shipping = Command::SHIPPING_FEE;
        $total = $subtotal + $shipping;

        return view('shop.cart-checkout', compact('items', 'subtotal', 'shipping', 'total'));
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

        $articleIds = collect($cart)->pluck('article_id')->unique();
        $articles = Article::whereIn('id', $articleIds)->get()->keyBy('id');

        $quantitiesByArticle = collect($cart)->groupBy('article_id')->map(fn (Collection $lines) => $lines->sum('quantity'));

        foreach ($quantitiesByArticle as $articleId => $totalQuantity) {
            $article = $articles->get($articleId);
            if (!$article || $article->quantity < $totalQuantity) {
                return redirect()->route('cart.index')
                    ->with('error', __('site.flash_insufficient_stock', ['title' => $article->title ?? __('site.an_item')]));
            }
        }

        $groupId = (string) Str::uuid();

        foreach ($cart as $entry) {
            Command::create($validated + [
                'group_id' => $groupId,
                'article_id' => $entry['article_id'],
                'color' => $entry['color'],
                'quantity' => $entry['quantity'],
                'shipping_fee' => Command::SHIPPING_FEE,
            ]);
            $articles->get($entry['article_id'])->decrement('quantity', $entry['quantity']);
        }

        session()->forget('cart');

        return redirect()->route('shop.home')
            ->with('success', __('site.flash_order_placed'));
    }

    private function cartKey(int $articleId, ?string $color): string
    {
        return $articleId.'-'.($color ? ltrim($color, '#') : 'none');
    }

    private function buildCartItems(array $cart): Collection
    {
        $articleIds = collect($cart)->pluck('article_id')->unique();
        $articles = Article::with('category')->whereIn('id', $articleIds)->get()->keyBy('id');

        return collect($cart)
            ->map(function ($entry, $key) use ($articles) {
                $article = $articles->get($entry['article_id']);

                if (!$article) {
                    return null;
                }

                return [
                    'key' => $key,
                    'article' => $article,
                    'color' => $entry['color'],
                    'quantity' => $entry['quantity'],
                    'subtotal' => $article->price * $entry['quantity'],
                ];
            })
            ->filter()
            ->values();
    }
}
