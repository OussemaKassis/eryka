@extends('layouts.app')

@section('hero-title', $activeCategory ? $activeCategory->title : 'All Products')
@section('hero-subtitle', $activeCategory ? null : 'Browse our full catalog, or filter by category.')

@section('content')
<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('shop.products') }}" class="btn btn-sm {{ !$activeCategory ? 'btn-primary' : '' }}">All Products</a>
                    @foreach($familyCategories as $family)
                        <a href="{{ route('shop.products', ['category' => $family->id]) }}" class="btn btn-sm {{ $activeCategory?->id === $family->id ? 'btn-primary' : '' }}">{{ $family->title }}</a>
                        @foreach($family->children as $child)
                            <a href="{{ route('shop.products', ['category' => $child->id]) }}" class="btn btn-sm {{ $activeCategory?->id === $child->id ? 'btn-primary' : '' }}">&nbsp;&nbsp;{{ $child->title }}</a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($articles as $article)
                @include('shop.partials.product-card', ['article' => $article])
            @empty
                <div class="col-12 text-center">
                    <p>No products found in this category.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
