@extends('layouts.app')

@section('hero-title', __('site.order_success_title'))

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center order-success-state">
                <div class="order-success-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 class="h3 mb-3 text-black">{{ __('site.order_success_title') }}</h1>
                <p class="mb-4">{{ __('site.order_success_message') }}</p>
                <a href="{{ route('shop.home') }}" class="btn btn-primary">{{ __('site.error_404_home') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
