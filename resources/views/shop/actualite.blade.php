@extends('layouts.app')

@section('hero-title', $pageHero->title ?? __('site.actualite_hero_title'))
@section('hero-subtitle', $pageHero->subtitle ?? __('site.actualite_hero_subtitle'))

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
@include('shop.partials.page-sections', ['sections' => $pageSections])

<div class="blog-section before-footer-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="section-title">{{ __('site.news_section_title') }}</h2>
            </div>
        </div>

        <div class="row">
            @forelse($newsItems as $item)
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="post-entry">
                        <a href="{{ $item->link_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="post-thumbnail"><img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="img-fluid"></span>
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
            @empty
                <div class="col-12 text-center">
                    <p>{{ __('site.no_news_yet') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
