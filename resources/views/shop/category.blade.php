@extends('layouts.app')

@section('hero-title', $category->title)
@section('hero-subtitle', $category->parent ? $category->parent->title . ' › ' . $category->title : ($category->description ?? null))

@section('content')
<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <a href="{{ route('shop.products') }}" class="btn btn-sm mb-5">&larr; {{ __('site.all_products') }}</a>

        @if($category->parent)
            <p class="mb-5">
                <a href="{{ route('shop.category', $category->parent->id) }}">{{ $category->parent->title }}</a>
                <span class="mx-1">›</span>
                <span>{{ $category->title }}</span>
            </p>
        @endif

        @if($category->children->isNotEmpty())
            <h2 class="section-title mb-4">{{ __('site.sous_familles') }}</h2>
            <div class="row mb-5">
                @foreach($category->children as $child)
                    <div class="col-12 col-md-4 col-lg-3 mb-4">
                        <a href="{{ route('shop.category', $child->id) }}" class="btn w-100">{{ $child->title }}</a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="row">
            @forelse($articles as $article)
                @include('shop.partials.product-card', ['article' => $article])
            @empty
                <div class="col-12 text-center">
                    <p>{{ __('site.no_products_in_category') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
