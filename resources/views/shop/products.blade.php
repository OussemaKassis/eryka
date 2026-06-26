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
@else
@endif

@section('content')
@include('shop.partials.page-sections', ['sections' => $pageSections])

<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="category-switcher" id="category-switcher">
                    <a href="{{ route('shop.products') }}" data-category="" class="category-pill {{ !$activeCategory ? 'active' : '' }}">{{ __('site.all_products') }}</a>
                    @foreach($familyCategories as $family)
                        <a href="{{ route('shop.products', ['category' => $family->id]) }}" data-category="{{ $family->id }}" class="category-pill {{ $activeCategory?->id === $family->id ? 'active' : '' }}">{{ $family->title }}</a>
                        @foreach($family->children as $child)
                            <a href="{{ route('shop.products', ['category' => $child->id]) }}" data-category="{{ $child->id }}" class="category-pill sub {{ $activeCategory?->id === $child->id ? 'active' : '' }}">{{ $child->title }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div class="products-toolbar mb-4">
            <form id="search-form" class="toolbar-search" onsubmit="return false;">
                <i class="fa-solid fa-magnifying-glass toolbar-search-icon"></i>
                <input type="text" id="search-input" class="form-control" placeholder="{{ __('site.search_placeholder') }}" autocomplete="off">
                <button type="submit" id="search-btn" class="btn btn-primary btn-sm">{{ __('site.search') }}</button>
            </form>

            <div class="toolbar-divider"></div>

            <div class="toolbar-price">
                <span class="toolbar-label">{{ __('site.price_filter') }}</span>
                <div class="toolbar-price-inputs">
                    <span class="toolbar-price-currency">DT</span>
                    <input type="number" id="price-min" class="form-control" placeholder="{{ __('site.min_price') }}" min="0" inputmode="numeric">
                    <span class="toolbar-price-sep">&ndash;</span>
                    <span class="toolbar-price-currency">DT</span>
                    <input type="number" id="price-max" class="form-control" placeholder="{{ __('site.max_price') }}" min="0" inputmode="numeric">
                </div>
            </div>

            <div class="toolbar-divider"></div>

            <select id="sort-select" class="form-select form-select-sm toolbar-sort">
                <option value="newest">{{ __('site.sort_newest') }}</option>
                <option value="price_asc">{{ __('site.sort_price_asc') }}</option>
                <option value="price_desc">{{ __('site.sort_price_desc') }}</option>
                <option value="name_asc">{{ __('site.sort_name_asc') }}</option>
            </select>

            <label class="stock-filter-toggle">
                <input type="checkbox" id="stock-filter">
                <span>{{ __('site.in_stock_only') }}</span>
            </label>
        </div>

        <div class="row" id="products-grid">
            @include('shop.partials.products-grid')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var switcher = document.getElementById('category-switcher');
    var grid = document.getElementById('products-grid');
    var sortSelect = document.getElementById('sort-select');
    var stockFilter = document.getElementById('stock-filter');
    var searchForm = document.getElementById('search-form');
    var searchInput = document.getElementById('search-input');
    var priceMin = document.getElementById('price-min');
    var priceMax = document.getElementById('price-max');
    var heroTitle = document.querySelector('.hero h1');
    var heroSubtitle = document.querySelector('.hero .intro-excerpt p');
    var baseUrl = '{{ route('shop.products') }}';
    if (!switcher || !grid) return;

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
        switcher.querySelectorAll('.category-pill').forEach(function(pill) {
            pill.classList.toggle('active', pill.getAttribute('data-category') === state.category);
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

    switcher.addEventListener('click', function(e) {
        var pill = e.target.closest('.category-pill');
        if (!pill) return;
        e.preventDefault();
        state.category = pill.getAttribute('data-category') || '';
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

    window.addEventListener('popstate', function() {
        state = readStateFromLocation();
        load(false);
    });
})();
</script>
@endpush
