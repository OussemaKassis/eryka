@extends('layouts.app')

@section('hero-title', $category->title)
@section('hero-subtitle', $category->description ?? null)

@section('content')
<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <a href="{{ route('shop.products') }}" class="btn btn-sm mb-5">&larr; {{ __('site.all_products') }}</a>

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
