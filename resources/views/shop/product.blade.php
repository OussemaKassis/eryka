@extends('layouts.app')

@section('hero-title', $article->title)

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <a href="{{ url('/') }}" class="btn btn-sm mb-5">&larr; Back to Shop</a>

        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <div class="p-3 p-lg-4 border bg-white">
                    <div class="product-thumbnail slider-container" style="height: 400px;">
                        @if($article->quantity <= 0)
                            <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">Out of Stock</span>
                        @elseif($article->quantity <= 5)
                            <span class="badge bg-warning text-dark position-absolute" style="top: 10px; left: 10px; z-index: 11;">Only {{ $article->quantity }} left</span>
                        @endif

                        <div class="slider" id="slider-{{ $article->id }}">
                            @if($article->images->count() > 0)
                                @foreach($article->images as $image)
                                    <div class="slide"
                                         style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
                                    </div>
                                @endforeach
                            @else
                                <div class="slide" style="background: #dce5e4;"></div>
                            @endif
                        </div>

                        @if($article->images->count() > 1)
                            <button class="slider-arrow prev" type="button" onclick="moveSlide('{{ $article->id }}', -1)">&#10094;</button>
                            <button class="slider-arrow next" type="button" onclick="moveSlide('{{ $article->id }}', 1)">&#10095;</button>
                            <div class="slider-nav" id="slider-nav-{{ $article->id }}">
                                @foreach($article->images as $key => $image)
                                    <span class="slider-dot {{ $loop->first ? 'active' : '' }}"
                                          onclick="goToSlide('{{ $article->id }}', {{ $key }})"></span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <p class="mb-1">
                    @if($article->category)
                        <a href="{{ route('shop.category', $article->category->id) }}" class="text-black">{{ $article->category->title }}</a>
                    @else
                        <a href="{{ route('shop.home') }}" class="text-black">Shop</a>
                    @endif
                </p>
                <h1 class="h2 text-black mb-3">{{ $article->title }}</h1>
                <strong class="product-price d-block mb-2" style="font-size: 1.75rem;">${{ number_format($article->price, 2) }}</strong>

                @if($article->quantity <= 0)
                    <span class="badge bg-danger mb-4">Out of Stock</span>
                @elseif($article->quantity <= 5)
                    <span class="badge bg-warning text-dark mb-4">Only {{ $article->quantity }} left</span>
                @else
                    <span class="badge bg-success mb-4">In Stock</span>
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
                            <label for="quantity" class="text-black">Quantity</label>
                            <input type="number" id="quantity" name="quantity" min="1" max="{{ $article->quantity }}" value="1" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>

                    <a href="{{ route('shop.checkout', $article->id) }}" class="btn btn-secondary">Buy Now</a>
                @else
                    <span class="btn disabled">Out of Stock</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
