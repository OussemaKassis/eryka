@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shop Articles</h1>
    <div class="articles-grid">
        @foreach($articles as $article)
            <div class="article-card">
                <div class="slider-container">
                    <div class="slider" id="slider-{{ $article->id }}">
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
                        <button class="slider-arrow prev" onclick="moveSlide('{{ $article->id }}', -1)">❮</button>
                        <button class="slider-arrow next" onclick="moveSlide('{{ $article->id }}', 1)">❯</button>
                        <div class="slider-nav" id="slider-nav-{{ $article->id }}">
                            @foreach($article->images as $key => $image)
                                <span class="slider-dot {{ $loop->first ? 'active' : '' }}" 
                                      onclick="goToSlide('{{ $article->id }}', {{ $key }})"></span>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <div class="article-content">
                    <h2 class="article-title">{{ $article->title }}</h2>
                    <p class="article-description">{{ $article->description }}</p>
                    <p class="article-price">${{ number_format($article->price, 2) }}</p>
                    <a href="{{ route('shop.checkout', $article->id) }}" class="btn">Order Now</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    const sliders = {};

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sliders
        document.querySelectorAll('.slider').forEach(slider => {
            const articleId = slider.id.split('-')[1];
            sliders[articleId] = {
                currentSlide: 0,
                totalSlides: slider.children.length
            };
        });
    });

    function moveSlide(articleId, direction) {
        const slider = sliders[articleId];
        slider.currentSlide = (slider.currentSlide + direction + slider.totalSlides) % slider.totalSlides;
        updateSlider(articleId);
    }

    function goToSlide(articleId, slideIndex) {
        sliders[articleId].currentSlide = slideIndex;
        updateSlider(articleId);
    }

    function updateSlider(articleId) {
        const slider = sliders[articleId];
        const sliderElement = document.getElementById(`slider-${articleId}`);
        const dots = document.querySelectorAll(`#slider-nav-${articleId} .slider-dot`);
        
        // Update slide position
        sliderElement.style.transform = `translateX(-${slider.currentSlide * 100}%)`;
        
        // Update active dot
        dots.forEach((dot, index) => {
            if (index === slider.currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }
</script>
@endpush
@endsection