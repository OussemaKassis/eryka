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
@include('shop.partials.page-sections', ['sections' => $pageSections])
@endsection
