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
        $shipping = $items->isEmpty() ? 0 : Command::shippingFee();
        $total = $subtotal + $shipping;

        return view('shop.cart', compact('items', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request, $article)
    {
        $article = Article::findOrFail($article);
        $color = $request->input('color') ?: null;
        $stock = $article->quantityForColor($color);

        if ($stock <= 0) {
            $message = __('site.flash_out_of_stock', ['title' => $article->title]);

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $key = $this->cartKey($article->id, $color);

        $cart = session('cart', []);
        $requested = max(1, (int) $request->input('quantity', 1));
        $existingQuantity = $cart[$key]['quantity'] ?? 0;

        $cart[$key] = [
            'article_id' => $article->id,
            'color' => $color,
            'quantity' => min($stock, $existingQuantity + $requested),
        ];

        session(['cart' => $cart]);

        if ($request->wantsJson()) {
            $items = $this->buildCartItems($cart);
            $subtotal = $items->sum('subtotal');
            $shipping = Command::shippingFee();
            $count = $items->count();

            $colorImages = $article->images->whereNotNull('color');
            $image = $color ? $colorImages->firstWhere('color', $color) : $article->images->first();

            return response()->json([
                'success' => true,
                'article' => [
                    'title' => $article->title,
                    'price' => $article->price,
                    'description' => $article->description,
                    'image' => $image ? asset('storage/' . $image->image_path) : null,
                ],
                'color' => $color,
                'quantity' => $cart[$key]['quantity'],
                'cart' => [
                    'countLabel' => trans_choice('site.cart_confirm_items_count', $count, ['count' => $count]),
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'total' => $subtotal + $shipping,
                ],
            ]);
        }

        return back()->with('success', __('site.flash_added_to_cart', ['title' => $article->title]));
    }

    public function update(Request $request, $key)
    {
        $cart = session('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('cart.index');
        }

        $article = Article::findOrFail($cart[$key]['article_id']);
        $stock = $article->quantityForColor($cart[$key]['color'] ?? null);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = min($quantity, $stock);
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
        $shipping = Command::shippingFee();
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

        // Each cart entry is already unique per (article, color), so stock is
        // checked per entry rather than summed across an article's colors —
        // an article with 3 red + 2 blue in the cart needs 3 in stock for
        // red and 2 for blue, not 5 of either.
        foreach ($cart as $entry) {
            $article = $articles->get($entry['article_id']);
            $stock = $article ? $article->quantityForColor($entry['color']) : 0;

            if ($stock < $entry['quantity']) {
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
                'shipping_fee' => Command::shippingFee(),
            ]);

            $article = $articles->get($entry['article_id']);
            $colorImages = $article->images->whereNotNull('color');
            $image = $entry['color'] ? $colorImages->firstWhere('color', $entry['color']) : null;

            if ($image) {
                $image->decrement('quantity', $entry['quantity']);
            } else {
                $article->decrement('quantity', $entry['quantity']);
            }
        }

        session()->forget('cart');

        return redirect()->route('order.success')->with('order_placed', true);
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
