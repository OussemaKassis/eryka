@extends('layouts.app')

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center error-404-state">
                <div class="error-404-number">404</div>
                <h1 class="h3 mb-3 text-black">{{ __('site.error_404_title') }}</h1>
                <p class="mb-4">{{ __('site.error_404_desc') }}</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('shop.home') }}" class="btn btn-primary">{{ __('site.error_404_home') }}</a>
                    <a href="{{ route('shop.products') }}" class="btn btn-secondary">{{ __('site.view_all_products') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
