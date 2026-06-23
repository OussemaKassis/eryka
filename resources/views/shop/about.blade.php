@extends('layouts.app')

@section('hero-title', 'About Us')
@section('hero-subtitle', 'Quality furniture and home essentials, delivered straight to your door.')
@section('hero-image')
    <img src="{{ asset('vendor/furni/images/couch.png') }}" class="img-fluid">
@endsection

@section('content')
<div class="why-choose-section before-footer-section">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Why Choose {{ config('app.name', 'Us') }}</h2>
                <p class="mb-4">We hand-pick every piece in our collection for comfort, durability, and timeless design, so your home feels as good as it looks.</p>

                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/truck.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Fast &amp; Free Shipping</h3>
                            <p>Every order ships fast, at no extra cost to you.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/bag.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Easy to Shop</h3>
                            <p>Browse, pick a piece, and place your order in seconds.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/support.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>24/7 Support</h3>
                            <p>Questions about an order? We're here to help anytime.</p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('vendor/furni/images/return.svg') }}" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Hassle Free Returns</h3>
                            <p>Not the right fit? Returns are simple and stress-free.</p>
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
