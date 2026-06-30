@php
    $outOfStock = $article->quantity <= 0;
    $lowStock = !$outOfStock && $article->quantity <= 5;
    $cardColors = $article->images->pluck('color')->filter()->unique()->values();
    $cardColorsMax = 4;
@endphp

<div class="col-12 col-md-4 col-lg-3 mb-5">
    <div class="product-item">
        <a href="{{ route('shop.product', $article->id) }}" class="d-block text-decoration-none">
            <div class="product-thumbnail slider-container">
                @if($outOfStock)
                    <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.out_of_stock') }}</span>
                @elseif($lowStock)
                    <span class="badge bg-warning text-dark position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.only_x_left', ['qty' => $article->quantity]) }}</span>
                @endif

                <div class="slider" id="slider-{{ $article->id }}">
                    @if($article->images->count() > 0)
                        @foreach($article->images as $image)
                            <div class="slide"
                                 style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
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

        @if($cardColors->isNotEmpty())
            <div class="product-color-swatches">
                @foreach($cardColors->take($cardColorsMax) as $color)
                    <span class="color-swatch color-swatch-sm" style="background-color: {{ $color }};" title="{{ $color }}"></span>
                @endforeach
                @if($cardColors->count() > $cardColorsMax)
                    <span class="product-color-more">+{{ $cardColors->count() - $cardColorsMax }}</span>
                @endif
            </div>
        @endif

        <div class="mt-3 d-flex flex-column align-items-center gap-2 product-actions">
            <a href="{{ route('shop.product', $article->id) }}" class="btn btn-outline-brand btn-sm">{{ __('site.view_full_details') }}</a>
        </div>
    </div>
</div>
