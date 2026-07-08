@extends('layouts.app')

@section('hero-title', $activeCategory ? $activeCategory->title : ($pageHero->title ?? __('site.all_products')))
@section('hero-subtitle', $activeCategory ? null : ($pageHero->subtitle ?? __('site.products_hero_subtitle')))

@if($heroSlides->isNotEmpty())
    @section('hero-bg-slides')
        @foreach($heroSlides as $slide)
            <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $slide->image_path) }}')"></div>
        @endforeach
    @endsection

    @if($heroSlides->count() > 1)
        @section('hero-slider-dots')
            <div class="hero-slider-dots">
                @foreach($heroSlides as $key => $slide)
                    <span class="hero-dot {{ $loop->first ? 'active' : '' }}" onclick="goToHeroSlide({{ $key }})"></span>
                @endforeach
            </div>
        @endsection
    @endif
@elseif($pageHero?->image_path)
    {{-- Single hero image (no Diaporama slides set) — still rendered full-width
         via the slider markup, rather than the layout's boxed side-image mode. --}}
    @section('hero-bg-slides')
        <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $pageHero->image_path) }}')"></div>
    @endsection
@endif

@section('content')
@include('shop.partials.page-sections', ['sections' => $pageSections])

<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-5 mb-lg-0 order-2 order-lg-1">
                <div class="shop-sidebar" id="shop-sidebar">
                    <div class="filter-block">
                        <h3 class="filter-title">{{ __('site.categories') }}</h3>
                        <div class="filter-category-list" id="category-filter-list">
                            <label class="filter-radio">
                                <input type="radio" name="category-filter" value="" {{ !$activeCategory ? 'checked' : '' }}>
                                <span>{{ __('site.all_categories') }}</span>
                            </label>
                            @foreach($familyCategories as $family)
                                <label class="filter-radio">
                                    <input type="radio" name="category-filter" value="{{ $family->id }}" {{ $activeCategory?->id === $family->id ? 'checked' : '' }}>
                                    <span>{{ $family->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-block">
                        <h3 class="filter-title">{{ __('site.search') }}</h3>
                        <form id="search-form" onsubmit="return false;">
                            <input type="text" id="search-input" class="form-control filter-search-input" placeholder="{{ __('site.search_placeholder') }}" autocomplete="off">
                        </form>
                    </div>

                    <div class="filter-block">
                        <h3 class="filter-title">{{ __('site.price_filter') }}</h3>
                        <div class="filter-price-inputs">
                            <input type="number" id="price-min" class="form-control" placeholder="{{ __('site.min_price') }}" min="0" inputmode="numeric">
                            <span class="filter-price-sep">&ndash;</span>
                            <input type="number" id="price-max" class="form-control" placeholder="{{ __('site.max_price') }}" min="0" inputmode="numeric">
                        </div>
                    </div>

                    <div class="filter-block">
                        <label class="stock-filter-toggle">
                            <input type="checkbox" id="stock-filter">
                            <span>{{ __('site.in_stock_only') }}</span>
                        </label>
                    </div>

                    <button type="button" id="filter-reset-btn" class="btn filter-reset-btn w-100">{{ __('site.reset_filters') }}</button>
                </div>
            </div>

            <div class="col-lg-9 order-1 order-lg-2">
                <div class="products-grid-header">
                    <p class="products-count" id="products-count">{{ __('site.products_found', ['count' => $articles->count()]) }}</p>
                    <div class="products-sort">
                        <span class="products-sort-label">{{ __('site.sort_by') }}</span>
                        <select id="sort-select" class="form-select form-select-sm products-sort-select">
                            <option value="newest">{{ __('site.sort_newest') }}</option>
                            <option value="price_asc">{{ __('site.sort_price_asc') }}</option>
                            <option value="price_desc">{{ __('site.sort_price_desc') }}</option>
                            <option value="name_asc">{{ __('site.sort_name_asc') }}</option>
                        </select>
                    </div>
                </div>

                <div class="row" id="products-grid">
                    @include('shop.partials.products-grid')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var sidebar = document.getElementById('shop-sidebar');
    var grid = document.getElementById('products-grid');
    var sortSelect = document.getElementById('sort-select');
    var stockFilter = document.getElementById('stock-filter');
    var searchForm = document.getElementById('search-form');
    var searchInput = document.getElementById('search-input');
    var priceMin = document.getElementById('price-min');
    var priceMax = document.getElementById('price-max');
    var resetBtn = document.getElementById('filter-reset-btn');
    var countEl = document.getElementById('products-count');
    var heroTitle = document.querySelector('.hero h1');
    var heroSubtitle = document.querySelector('.hero .intro-excerpt p');
    var baseUrl = '{{ route('shop.products') }}';
    if (!sidebar || !grid) return;

    function readStateFromLocation() {
        var params = new URLSearchParams(window.location.search);
        return {
            category: params.get('category') || '',
            sort: params.get('sort') || 'newest',
            in_stock: params.get('in_stock') === '1',
            search: params.get('search') || '',
            min_price: params.get('min_price') || '',
            max_price: params.get('max_price') || '',
        };
    }

    var state = readStateFromLocation();

    function buildUrl() {
        var params = new URLSearchParams();
        if (state.category) params.set('category', state.category);
        if (state.sort && state.sort !== 'newest') params.set('sort', state.sort);
        if (state.in_stock) params.set('in_stock', '1');
        if (state.search) params.set('search', state.search);
        if (state.min_price) params.set('min_price', state.min_price);
        if (state.max_price) params.set('max_price', state.max_price);
        var qs = params.toString();
        return baseUrl + (qs ? '?' + qs : '');
    }

    function syncControls() {
        sortSelect.value = state.sort;
        stockFilter.checked = state.in_stock;
        searchInput.value = state.search;
        priceMin.value = state.min_price;
        priceMax.value = state.max_price;
        sidebar.querySelectorAll('input[name="category-filter"]').forEach(function(radio) {
            radio.checked = radio.value === state.category;
        });
    }

    function load(pushState) {
        var url = buildUrl();
        fetch(url, { headers: { Accept: 'application/json' } })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                grid.style.opacity = 0;
                setTimeout(function() {
                    grid.innerHTML = data.html;
                    if (heroTitle) heroTitle.textContent = data.title;
                    if (heroSubtitle) heroSubtitle.textContent = data.subtitle || '';
                    grid.style.opacity = 1;
                }, 200);
                if (countEl) countEl.textContent = data.count_label;
                syncControls();
                if (pushState) {
                    history.pushState({ url: url }, '', url);
                }
            })
            .catch(function() {
                window.location.href = url;
            });
    }

    grid.style.transition = 'opacity 0.2s ease';
    syncControls();

    sidebar.addEventListener('change', function(e) {
        if (e.target.name !== 'category-filter') return;
        state.category = e.target.value;
        load(true);
    });

    sortSelect.addEventListener('change', function() {
        state.sort = sortSelect.value;
        load(true);
    });

    stockFilter.addEventListener('change', function() {
        state.in_stock = stockFilter.checked;
        load(true);
    });

    var debounceTimer;
    function debounceLoad() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() { load(true); }, 450);
    }

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        state.search = searchInput.value.trim();
        load(true);
    });

    searchInput.addEventListener('input', function() {
        state.search = searchInput.value.trim();
        debounceLoad();
    });

    priceMin.addEventListener('input', function() {
        state.min_price = priceMin.value;
        debounceLoad();
    });

    priceMax.addEventListener('input', function() {
        state.max_price = priceMax.value;
        debounceLoad();
    });

    resetBtn.addEventListener('click', function() {
        state = { category: '', sort: 'newest', in_stock: false, search: '', min_price: '', max_price: '' };
        load(true);
    });

    window.addEventListener('popstate', function() {
        state = readStateFromLocation();
        load(false);
    });
})();
</script>
@endpush
