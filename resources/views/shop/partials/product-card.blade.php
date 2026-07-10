@php
    $outOfStock = $article->effective_quantity <= 0;
    $cardColors = $article->images->pluck('color')->filter()->unique()->values();
    $cardColorsMax = 4;
    $sliderItem = $sliderItem ?? false;
@endphp

<div class="{{ $sliderItem ? 'product-slide-item' : 'col-12 col-md-4 col-lg-3 mb-5' }}">
    <div class="product-item">
        <a href="{{ route('shop.product', $article->id) }}" class="d-block text-decoration-none">
            <div class="product-thumbnail slider-container">
                @if($outOfStock)
                    <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.out_of_stock') }}</span>
                @endif

                <div class="slider" id="slider-{{ $article->id }}">
                    @if($article->images->count() > 0)
                        @foreach($article->images as $image)
                            <div class="slide">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="{{ $article->title }}"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                        @endforeach
                    @else
                        <div class="slide slide-placeholder"><i class="fa-solid fa-couch"></i></div>
                    @endif
                </div>
            </div>

            <h3 class="product-title">{{ $article->title }}</h3>
            @if($article->category)
                <p class="product-category">{{ $article->category->title }}</p>
            @endif
        </a>
        <strong class="product-price">{{ number_format($article->price, 2) }} DT</strong>

        <div class="product-color-swatches">
            @if($cardColors->isNotEmpty())
                @foreach($cardColors->take($cardColorsMax) as $color)
                    <span class="color-swatch color-swatch-sm" style="background-color: {{ $color }};" title="{{ $color }}"></span>
                @endforeach
                @if($cardColors->count() > $cardColorsMax)
                    <span class="product-color-more">+{{ $cardColors->count() - $cardColorsMax }}</span>
                @endif
            @endif
        </div>

        <div class="d-flex flex-column align-items-center gap-2 product-actions">
            <a href="{{ route('shop.product', $article->id) }}" class="btn btn-outline-brand btn-sm">{{ __('site.view_full_details') }}</a>
        </div>
    </div>
</div>
