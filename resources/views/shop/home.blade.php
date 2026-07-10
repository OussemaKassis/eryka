@extends('layouts.app')

@section('hero-title', $pageHero->title ?? __('site.home_hero_title'))
@section('hero-subtitle', $pageHero->subtitle ?? __('site.home_hero_subtitle'))

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
@else
    @section('hero-image')
        <img src="{{ asset('vendor/furni/images/couch.png') }}" class="img-fluid">
    @endsection
@endif

@section('content')
<!-- Start We Help Section -->
<div class="we-help-section section-compact">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7 mb-5 mb-lg-0">
                @if($welcomeSection && $welcomeSection->video_path)
                    <video class="we-help-video" src="{{ asset('storage/' . $welcomeSection->video_path) }}" autoplay muted loop playsinline></video>
                @else
                    <div class="imgs-grid">
                        <div class="grid grid-1"><img src="{{ asset('vendor/furni/images/img-grid-1.jpg') }}" alt="{{ config('app.name', 'Shop') }}" loading="lazy" decoding="async"></div>
                        <div class="grid grid-2"><img src="{{ asset('vendor/furni/images/img-grid-2.jpg') }}" alt="{{ config('app.name', 'Shop') }}" loading="lazy" decoding="async"></div>
                        <div class="grid grid-3"><img src="{{ asset('vendor/furni/images/img-grid-3.jpg') }}" alt="{{ config('app.name', 'Shop') }}" loading="lazy" decoding="async"></div>
                    </div>
                @endif
            </div>
            <div class="col-lg-5 ps-lg-5">
                @if($welcomeSection)
                    <h2 class="section-title mb-4">{{ $welcomeSection->title }}</h2>
                    @foreach(explode("\n\n", $welcomeSection->body) as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    <p class="mt-4"><a href="{{ route('shop.about') }}" class="btn">{{ __('site.about_learn_more') }}</a></p>
                @else
                    <h2 class="section-title mb-4">{{ __('site.we_help_title') }}</h2>
                    <p>{{ __('site.we_help_desc') }}</p>

                    <ul class="list-unstyled custom-list my-4">
                        <li>{{ __('site.we_help_list_1') }}</li>
                        <li>{{ __('site.we_help_list_2') }}</li>
                        <li>{{ __('site.we_help_list_3') }}</li>
                        <li>{{ __('site.we_help_list_4') }}</li>
                    </ul>
                    <p><a href="#products" class="btn">{{ __('site.explore') }}</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End We Help Section -->

@include('shop.partials.page-sections', ['sections' => $pageSections])

<div id="products" class="untree_co-section product-section section-compact section-alt">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <h2 class="section-title">{{ __('site.featured_products') }}</h2>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-start justify-content-md-end align-items-center gap-3">
                <a href="{{ route('shop.products') }}" class="more">{{ __('site.view_all_products') }}</a>
            </div>
        </div>

        @if($articles->isEmpty())
            <p class="text-center">{{ __('site.no_products_yet') }}</p>
        @else
            <div class="product-slider-wrap">
                @if($articles->count() > 1)
                    <div class="product-slider-nav" id="product-slider-nav">
                        <button type="button" class="product-slider-arrow" data-controls="prev" aria-label="{{ __('site.previous') }}"><i class="fa-solid fa-chevron-left"></i></button>
                        <button type="button" class="product-slider-arrow" data-controls="next" aria-label="{{ __('site.next') }}"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                @endif
                <div class="product-slider" id="product-slider">
                    @foreach($articles as $article)
                        @include('shop.partials.product-card', ['article' => $article, 'sliderItem' => true])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="why-choose-section section-compact">
    <div class="container">
        <div class="row justify-content-center text-center mb-3">
            <div class="col-lg-7">
                <h2 class="section-title">{{ __('site.why_choose_us') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/truck.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>{{ __('site.feature_shipping_title') }}</h3>
                    <p>{{ __('site.feature_shipping_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/bag.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>{{ __('site.feature_easy_title') }}</h3>
                    <p>{{ __('site.feature_easy_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/support.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>{{ __('site.feature_support_title') }}</h3>
                    <p>{{ __('site.feature_support_desc') }}</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/return.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>{{ __('site.feature_returns_title') }}</h3>
                    <p>{{ __('site.feature_returns_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if($newsItems->isNotEmpty())
    <!-- Start News/Actualité Section -->
    <div class="blog-section section-compact-last section-alt">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <h2 class="section-title">{{ __('site.news_section_title') }}</h2>
                </div>
                <div class="col-md-6 text-start text-md-end">
                    <a href="{{ route('shop.actualite') }}" class="more">{{ __('site.view_all_news') }}</a>
                </div>
            </div>

            <div class="product-slider-wrap">
                @if($newsItems->count() > 1)
                    <div class="product-slider-nav" id="news-slider-nav">
                        <button type="button" class="product-slider-arrow" data-controls="prev" aria-label="{{ __('site.previous') }}"><i class="fa-solid fa-chevron-left"></i></button>
                        <button type="button" class="product-slider-arrow" data-controls="next" aria-label="{{ __('site.next') }}"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                @endif
                <div class="product-slider" id="news-slider">
                    @foreach($newsItems as $item)
                        <div class="product-slide-item">
                            <div class="post-entry">
                                <a href="{{ $item->link_url }}" target="_blank" rel="noopener noreferrer">
                                    <span class="post-thumbnail"><img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="img-fluid" loading="lazy" decoding="async"></span>
                                    <div class="post-content-entry">
                                        <h3>{{ $item->title }}</h3>
                                        @if($item->description)
                                            <div class="meta">
                                                <span>{{ $item->description }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End News/Actualité Section -->
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var slider = document.getElementById('product-slider');
        var nav = document.getElementById('product-slider-nav');
        if (!slider || typeof tns !== 'function') return;

        tns({
            container: slider,
            items: 1,
            gutter: 0,
            controlsContainer: nav ? '#product-slider-nav' : false,
            controls: !!nav,
            nav: false,
            loop: true,
            mouseDrag: true,
            speed: 400,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            autoplayButtonOutput: false,
            responsive: {
                576: { items: 2 },
                992: { items: 4 },
            },
        });

        var newsSlider = document.getElementById('news-slider');
        var newsNav = document.getElementById('news-slider-nav');
        if (!newsSlider || typeof tns !== 'function') return;

        tns({
            container: newsSlider,
            items: 1,
            gutter: 0,
            controlsContainer: newsNav ? '#news-slider-nav' : false,
            controls: !!newsNav,
            nav: false,
            loop: true,
            mouseDrag: true,
            speed: 400,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            autoplayButtonOutput: false,
            responsive: {
                576: { items: 2 },
                992: { items: 3 },
            },
        });
    });
</script>
@endpush
