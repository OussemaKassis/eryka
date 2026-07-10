<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Command;
use App\Models\ContactInfo;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\NewsItem;
use App\Models\PageHero;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    // Shared hero data (admin-managed slides + title/subtitle) for a given page key
    private function pageHero(string $pageKey): array
    {
        return [
            'pageHero' => PageHero::where('page_key', $pageKey)->first(),
            'heroSlides' => HeroSlide::where('page_key', $pageKey)->where('is_active', true)->orderBy('sort_order')->get(),
        ];
    }

    // Admin-managed text + picture sections for a given page key
    private function pageSections(string $pageKey)
    {
        return PageSection::where('page_key', $pageKey)->where('is_active', true)->orderBy('sort_order')->get();
    }

    // Homepage: featured articles + marketing sections
    public function articlesHome()
    {
        $articles = Article::with('category', 'images')->latest()->take(4)->get();
        $homeSections = $this->pageSections('home');
        $welcomeSection = $homeSections->first();
        $newsItems = NewsItem::where('is_active', true)->latest()->take(3)->get();
        return view('shop.home', [
            'articles' => $articles,
            'welcomeSection' => $welcomeSection,
            'pageSections' => $homeSections->skip(1),
            'newsItems' => $newsItems,
        ] + $this->pageHero('home'));
    }

    // All products, optionally filtered by category
    public function products(Request $request)
    {
        $query = Article::with('category', 'images');
        $activeCategory = null;

        if ($categoryId = $request->query('category')) {
            $activeCategory = Category::findOrFail($categoryId);
            $query->where('category_id', $activeCategory->id);
        }

        if ($request->boolean('in_stock')) {
            $query->where('quantity', '>', 0);
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where('title', 'like', '%'.$search.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        match ($request->query('sort')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('title', 'asc'),
            default => $query->latest(),
        };

        $articles = $query->get();
        $familyCategories = Category::orderBy('title')->get();
        $hero = $this->pageHero('products');

        if ($request->wantsJson()) {
            return response()->json([
                'title' => $activeCategory ? $activeCategory->title : ($hero['pageHero']->title ?? __('site.all_products')),
                'subtitle' => $activeCategory ? null : ($hero['pageHero']->subtitle ?? __('site.products_hero_subtitle')),
                'count_label' => __('site.products_found', ['count' => $articles->count()]),
                'html' => view('shop.partials.products-grid', compact('articles'))->render(),
            ]);
        }

        return view('shop.products', [
            'articles' => $articles,
            'familyCategories' => $familyCategories,
            'activeCategory' => $activeCategory,
            'pageSections' => $this->pageSections('products'),
        ] + $hero);
    }

    // Product detail page
    public function show($article)
    {
        $article = Article::with('category')->findOrFail($article);
        $shipping = Command::shippingFee();
        return view('shop.product', compact('article', 'shipping'));
    }

    // Checkout page for an article
    public function checkout($article)
    {
        $article = Article::with('category')->findOrFail($article);
        $shipping = Command::shippingFee();
        return view('shop.checkout', compact('article', 'shipping'));
    }

    // Order submission (POST)
    public function orderSubmit(Request $request, $article)
    {
        $article = Article::findOrFail($article);
        $stock = $article->effective_quantity;
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . max($stock, 0)],
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
        ], [
            'quantity.max' => $stock > 0
                ? "Only {$stock} left in stock."
                : 'This product is out of stock.',
        ]);
        $validated['article_id'] = $article->id;
        $validated['group_id'] = (string) Str::uuid();
        $validated['shipping_fee'] = Command::shippingFee();
        Command::create($validated);
        $article->decrement('quantity', $validated['quantity']);

        return redirect()->route('order.success')->with('order_placed', true);
    }

    // Order confirmation page — reached only right after a successful order;
    // the "order_placed" flash value is consumed on this request, so a
    // refresh or direct visit bounces back to the homepage instead of
    // re-showing a stale confirmation.
    public function orderSuccess()
    {
        if (!session('order_placed')) {
            return redirect()->route('shop.home');
        }

        return view('shop.order-success');
    }

    public function about()
    {
        $pageSections = $this->pageSections('about');
        return view('shop.about', ['pageSections' => $pageSections] + $this->pageHero('about'));
    }

    public function actualite()
    {
        $newsItems = NewsItem::where('is_active', true)->latest()->get();
        $pageSections = $this->pageSections('actualite');
        return view('shop.actualite', [
            'newsItems' => $newsItems,
            'pageSections' => $pageSections,
        ] + $this->pageHero('actualite'));
    }

    public function contact()
    {
        $contactInfos = ContactInfo::where('is_active', true)->orderBy('sort_order')->get();
        $pageSections = $this->pageSections('contact');
        return view('shop.contact', ['contactInfos' => $contactInfos, 'pageSections' => $pageSections] + $this->pageHero('contact'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string',
        ]);
        ContactMessage::create($validated);
        return redirect()->route('shop.contact')
            ->with('success', __('site.flash_contact_thanks'));
    }
}
