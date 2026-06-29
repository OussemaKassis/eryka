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
@else
    @section('hero-image')
        <img src="{{ asset('vendor/furni/images/couch.png') }}" class="img-fluid">
    @endsection
@endif

@section('content')
<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="section-title">{{ __('site.featured_products') }}</h2>
            </div>
            <div class="col-md-6 text-start text-md-end">
                <a href="{{ route('shop.products') }}" class="more">{{ __('site.view_all_products') }}</a>
            </div>
        </div>

        <div class="row">
            @forelse($articles as $article)
                @include('shop.partials.product-card', ['article' => $article])
            @empty
                <div class="col-12 text-center">
                    <p>{{ __('site.no_products_yet') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="why-choose-section before-footer-section">
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

<!-- Start We Help Section -->
<div class="we-help-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="imgs-grid">
                    <div class="grid grid-1"><img src="{{ asset('vendor/furni/images/img-grid-1.jpg') }}" alt="{{ config('app.name', 'Shop') }}"></div>
                    <div class="grid grid-2"><img src="{{ asset('vendor/furni/images/img-grid-2.jpg') }}" alt="{{ config('app.name', 'Shop') }}"></div>
                    <div class="grid grid-3"><img src="{{ asset('vendor/furni/images/img-grid-3.jpg') }}" alt="{{ config('app.name', 'Shop') }}"></div>
                </div>
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

<!-- Start Blog Section -->
<div class="blog-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="section-title">{{ __('site.tips_title') }}</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="post-entry">
                    <span class="post-thumbnail"><img src="{{ asset('vendor/furni/images/post-1.jpg') }}" alt="Image" class="img-fluid"></span>
                    <div class="post-content-entry">
                        <h3>{{ __('site.blog_1_title') }}</h3>
                        <div class="meta">
                            <span>{{ __('site.blog_1_meta') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="post-entry">
                    <span class="post-thumbnail"><img src="{{ asset('vendor/furni/images/post-2.jpg') }}" alt="Image" class="img-fluid"></span>
                    <div class="post-content-entry">
                        <h3>{{ __('site.blog_2_title') }}</h3>
                        <div class="meta">
                            <span>{{ __('site.blog_2_meta') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="post-entry">
                    <span class="post-thumbnail"><img src="{{ asset('vendor/furni/images/post-3.jpg') }}" alt="Image" class="img-fluid"></span>
                    <div class="post-content-entry">
                        <h3>{{ __('site.blog_3_title') }}</h3>
                        <div class="meta">
                            <span>{{ __('site.blog_3_meta') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Blog Section -->
@endsection
