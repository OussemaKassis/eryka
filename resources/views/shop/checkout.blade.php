@extends('layouts.app')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize checkout slider
        const checkoutSlider = document.getElementById('checkout-slider');
        if (checkoutSlider) {
            window.checkoutSlider = {
                currentSlide: 0,
                totalSlides: checkoutSlider.children.length
            };
        }
    });

    function moveSlide(sliderId, direction) {
        const slider = window[`${sliderId}Slider`];
        slider.currentSlide = (slider.currentSlide + direction + slider.totalSlides) % slider.totalSlides;
        updateSlider(sliderId);
    }

    function goToSlide(sliderId, slideIndex) {
        window[`${sliderId}Slider`].currentSlide = slideIndex;
        updateSlider(sliderId);
    }

    function updateSlider(sliderId) {
        const slider = window[`${sliderId}Slider`];
        const sliderElement = document.getElementById(`${sliderId}-slider`);
        const dots = document.querySelectorAll(`#${sliderId}-slider-nav .slider-dot`);
        
        // Update slide position
        if (sliderElement) {
            sliderElement.style.transform = `translateX(-${slider.currentSlide * 100}%)`;
        }
        
        // Update active dot
        if (dots.length > 0) {
            dots.forEach((dot, index) => {
                if (index === slider.currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
    }
</script>
@endpush

@section('content')
<div class="checkout-container">
    <a href="{{ url('/') }}" class="btn" style="margin-bottom: 1rem;">&larr; Back to Shop</a>
    
    <div class="checkout-card">
        <div class="checkout-content">
            <div class="slider-container" style="max-width: 400px; margin: 0 auto 2rem;">
                <div class="slider" id="checkout-slider">
                    @if($article->images->count() > 0)
                        @foreach($article->images as $image)
                            <div class="slide" 
                                 style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
                            </div>
                        @endforeach
                    @else
                        <div class="slide" style="background: #eee; display: flex; align-items: center; justify-content: center;">
                            No Image Available
                        </div>
                    @endif
                </div>
                
                @if($article->images->count() > 1)
                    <button class="slider-arrow prev" onclick="moveSlide('checkout', -1)">❮</button>
                    <button class="slider-arrow next" onclick="moveSlide('checkout', 1)">❯</button>
                    <div class="slider-nav" id="checkout-slider-nav">
                        @foreach($article->images as $key => $image)
                            <span class="slider-dot {{ $loop->first ? 'active' : '' }}" 
                                  onclick="goToSlide('checkout', {{ $key }})"></span>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <h1 class="checkout-title">{{ $article->title }}</h1>
            <p style="margin-bottom: 1.5rem;">{{ $article->description }}</p>
            <p class="article-price" style="margin-bottom: 1.5rem;">${{ $article->price }}</p>
            
            @if($errors->any())
                <div class="alert" style="background: #fee2e2; color: #b91c1c; margin-bottom: 1.5rem;">
                    <ul style="list-style: inside; padding-left: 0.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('shop.order.submit', $article->id) }}" method="POST">
                @csrf
                <div class="grid-cols-2">
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="customer_first_name">First Name</label>
                        <input type="text" id="customer_first_name" name="customer_first_name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="customer_last_name">Last Name</label>
                        <input type="text" id="customer_last_name" name="customer_last_name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" required class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required class="form-control" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Submit Order</button>
            </form>
        </div>
    </div>
</div>
@endsection