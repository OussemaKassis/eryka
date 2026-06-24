@extends('layouts.app')

@section('hero-title', $pageHero->title ?? __('site.about_hero_title'))
@section('hero-subtitle', $pageHero->subtitle ?? __('site.footer_tagline'))

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
<div class="why-choose-section before-footer-section">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">{{ __('site.why_choose_name', ['name' => config('app.name', 'Us')]) }}</h2>
                <p class="mb-4">{{ __('site.about_lead') }}</p>

                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/truck.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>{{ __('site.feature_shipping_title') }}</h3>
                            <p>{{ __('site.feature_shipping_desc') }}</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/bag.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>{{ __('site.feature_easy_title') }}</h3>
                            <p>{{ __('site.feature_easy_desc') }}</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/support.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>{{ __('site.feature_support_title') }}</h3>
                            <p>{{ __('site.feature_support_desc') }}</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
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

            <div class="col-lg-5">
                <div class="img-wrap">
                    <img src="{{ asset('vendor/furni/images/why-choose-us-img.jpg') }}" alt="Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
