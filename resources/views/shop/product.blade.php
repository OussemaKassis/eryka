@extends('layouts.app')

@section('hero-title', $article->title)

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <a href="{{ url('/') }}" class="btn btn-sm mb-5">&larr; {{ __('site.back_to_shop') }}</a>

        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <div class="p-3 p-lg-4 border bg-white">
                    <div class="product-thumbnail slider-container" style="height: 400px;">
                        @if($article->quantity <= 0)
                            <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.out_of_stock') }}</span>
                        @elseif($article->quantity <= 5)
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

                        @if($article->images->count() > 1)
                            <button class="slider-arrow prev" type="button" onclick="moveSlide('{{ $article->id }}', -1)">&#10094;</button>
                            <button class="slider-arrow next" type="button" onclick="moveSlide('{{ $article->id }}', 1)">&#10095;</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <p class="mb-1">
                    @if($article->category)
                        <a href="{{ route('shop.category', $article->category->id) }}" class="text-black">{{ $article->category->title }}</a>
                    @else
                        <a href="{{ route('shop.home') }}" class="text-black">{{ __('site.shop') }}</a>
                    @endif
                </p>
                <h1 class="h2 text-black mb-3">{{ $article->title }}</h1>

                @if($article->images->whereNotNull('color')->isNotEmpty())
                    <div class="mb-4">
                        <h3 class="h6 text-black mb-2">{{ __('site.color') }}</h3>
                        <div class="d-flex gap-2">
                            @foreach($article->images as $key => $image)
                                @if($image->color)
                                    <button type="button"
                                            class="color-swatch {{ $loop->first ? 'active' : '' }}"
                                            style="background-color: {{ $image->color }};"
                                            title="{{ $image->color }}"
                                            aria-label="{{ __('site.choose_color', ['color' => $image->color]) }}"
                                            onclick="selectColorSwatch('{{ $article->id }}', {{ $key }}, this)">
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <strong class="product-price d-block mb-2" style="font-size: 1.75rem;">${{ number_format($article->price, 2) }}</strong>

                @if($article->quantity <= 0)
                    <span class="badge bg-danger mb-4">{{ __('site.out_of_stock') }}</span>
                @elseif($article->quantity <= 5)
                    <span class="badge bg-warning text-dark mb-4">{{ __('site.only_x_left', ['qty' => $article->quantity]) }}</span>
                @else
                    <span class="badge bg-success mb-4">{{ __('site.in_stock') }}</span>
                @endif

                @if($article->description)
                    <p class="mb-4">{{ $article->description }}</p>
                @endif

                @if($article->detail)
                    <div class="mb-4">{!! $article->detail !!}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($article->quantity > 0)
                    <form action="{{ route('cart.add', $article->id) }}" method="POST" class="d-flex align-items-end gap-2 mb-3 flex-wrap">
                        @csrf
                        <div class="form-group mb-0" style="max-width: 120px;">
                            <label for="quantity" class="text-black">{{ __('site.quantity') }}</label>
                            <input type="number" id="quantity" name="quantity" min="1" max="{{ $article->quantity }}" value="1" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('site.add_to_cart') }}</button>
                    </form>

                    <a href="{{ route('shop.checkout', $article->id) }}" class="btn btn-secondary">{{ __('site.buy_now') }}</a>
                @else
                    <span class="btn disabled">{{ __('site.out_of_stock') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectColorSwatch(articleId, index, el) {
        goToSlide(articleId, index);
        el.parentElement.querySelectorAll('.color-swatch').forEach(function(swatch) {
            swatch.classList.remove('active');
        });
        el.classList.add('active');
    }
</script>
@endpush
