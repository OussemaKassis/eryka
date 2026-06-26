<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Command;
use App\Models\ContactInfo;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
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
        $articles = Article::with('category')->latest()->take(8)->get();
        $homeSections = $this->pageSections('home');
        $welcomeSection = $homeSections->first();
        return view('shop.home', [
            'articles' => $articles,
            'welcomeSection' => $welcomeSection,
            'pageSections' => $homeSections->skip(1),
        ] + $this->pageHero('home'));
    }

    // All products, optionally filtered by category (and its sub-categories)
    public function products(Request $request)
    {
        $query = Article::with('category');
        $activeCategory = null;

        if ($categoryId = $request->query('category')) {
            $activeCategory = Category::with('children')->findOrFail($categoryId);

            $categoryIds = $activeCategory->children->isNotEmpty()
                ? $activeCategory->children->pluck('id')->push($activeCategory->id)
                : [$activeCategory->id];

            $query->whereIn('category_id', $categoryIds);
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
        $familyCategories = Category::topLevel()->with('children')->orderBy('title')->get();
        $hero = $this->pageHero('products');

        if ($request->wantsJson()) {
            return response()->json([
                'title' => $activeCategory ? $activeCategory->title : ($hero['pageHero']->title ?? __('site.all_products')),
                'subtitle' => $activeCategory ? null : ($hero['pageHero']->subtitle ?? __('site.products_hero_subtitle')),
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
        $shipping = Command::SHIPPING_FEE;
        return view('shop.product', compact('article', 'shipping'));
    }

    // Checkout page for an article
    public function checkout($article)
    {
        $article = Article::with('category')->findOrFail($article);
        $shipping = Command::SHIPPING_FEE;
        return view('shop.checkout', compact('article', 'shipping'));
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
        $validated['group_id'] = (string) Str::uuid();
        $validated['shipping_fee'] = Command::SHIPPING_FEE;
        Command::create($validated);
        $article->decrement('quantity', $validated['quantity']);

        return redirect()->route('shop.checkout', $article->id)
            ->with('success', 'Your order has been submitted!');
    }

    public function about()
    {
        $pageSections = $this->pageSections('about');
        return view('shop.about', ['pageSections' => $pageSections] + $this->pageHero('about'));
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
            ->with('success', 'Thanks for reaching out! We will get back to you soon.');
    }
}
