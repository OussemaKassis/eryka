@extends('layouts.app')

@section('hero-title', 'Our Furniture Collection')
@section('hero-subtitle', 'Discover quality pieces crafted with care, delivered straight to your door.')
@section('hero-image')
    <img src="{{ asset('vendor/furni/images/couch.png') }}" class="img-fluid">
@endsection

@section('content')
<div id="products" class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="section-title">Featured Products</h2>
            </div>
            <div class="col-md-6 text-start text-md-end">
                <a href="{{ route('shop.products') }}" class="more">View All Products</a>
            </div>
        </div>

        <div class="row">
            @forelse($articles as $article)
                @include('shop.partials.product-card', ['article' => $article])
            @empty
                <div class="col-12 text-center">
                    <p>No products available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="why-choose-section before-footer-section">
    <div class="container">
        <div class="row justify-content-center text-center mb-3">
            <div class="col-lg-7">
                <h2 class="section-title">Why Choose Us</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/truck.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Fast &amp; Free Shipping</h3>
                    <p>Every order ships fast, at no extra cost to you.</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/bag.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Easy to Shop</h3>
                    <p>Browse, pick a piece, and place your order in seconds.</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature">
                    <div class="icon">
                        <img src="{{ asset('vendor/furni/images/support.svg') }}" alt="Image" class="imf-fluid">
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Questions about an order? We're here to help anytime.</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
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
                <h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
                <p>Every piece in our collection is chosen to mix comfort with style, so you can build a home that feels as good as it looks.</p>

                <ul class="list-unstyled custom-list my-4">
                    <li>Hand-picked, quality-checked furniture</li>
                    <li>Fast, reliable delivery to your door</li>
                    <li>Friendly support before and after your order</li>
                    <li>Simple, hassle-free returns</li>
                </ul>
                <p><a href="#products" class="btn">Explore</a></p>
            </div>
        </div>
    </div>
</div>
<!-- End We Help Section -->

<!-- Start Popular Product -->
@if($articles->count() > 0)
<div class="popular-product">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="section-title">Popular Picks</h2>
            </div>
        </div>

        <div class="row">
            @foreach($articles->take(3) as $article)
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <a href="{{ route('shop.product', $article->id) }}" class="product-item-sm d-flex text-decoration-none">
                        <div class="thumbnail">
                            @if($article->images->first())
                                <img src="{{ asset('storage/' . $article->images->first()->image_path) }}" alt="{{ $article->title }}" class="img-fluid">
                            @endif
                        </div>
                        <div class="pt-3">
                            <h3>{{ $article->title }}
                                @if($article->quantity <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </h3>
                            <p>{{ $article->description ?? 'A quality piece from our collection.' }}</p>
                            <p class="mb-0">Read More</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- End Popular Product -->

<!-- Start Testimonial Slider -->
<div class="testimonial-section before-footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto text-center">
                <h2 class="section-title">What Our Customers Say</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="testimonial-slider-wrap text-center">

                    <div id="testimonial-nav">
                        <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                        <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                    </div>

                    <div class="testimonial-slider">

                        <div class="item">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 mx-auto">
                                    <div class="testimonial-block text-center">
                                        <blockquote class="mb-5">
                                            <p>&ldquo;Beautiful furniture and the delivery was faster than I expected. The sofa looks even better in my living room than it did online.&rdquo;</p>
                                        </blockquote>
                                        <div class="author-info">
                                            <div class="author-pic">
                                                <img src="{{ asset('vendor/furni/images/person_4.jpg') }}" alt="Sarah M." class="img-fluid">
                                            </div>
                                            <h3 class="font-weight-bold">Sarah M.</h3>
                                            <span class="position d-block mb-3">Verified Buyer</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 mx-auto">
                                    <div class="testimonial-block text-center">
                                        <blockquote class="mb-5">
                                            <p>&ldquo;Ordering was simple and the quality is exactly what was advertised. Already planning my next order with {{ config('app.name', 'this shop') }}.&rdquo;</p>
                                        </blockquote>
                                        <div class="author-info">
                                            <div class="author-pic">
                                                <img src="{{ asset('vendor/furni/images/person_1.jpg') }}" alt="David K." class="img-fluid">
                                            </div>
                                            <h3 class="font-weight-bold">David K.</h3>
                                            <span class="position d-block mb-3">Verified Buyer</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 mx-auto">
                                    <div class="testimonial-block text-center">
                                        <blockquote class="mb-5">
                                            <p>&ldquo;Great customer support and a smooth checkout. Exactly the easy shopping experience I was hoping for.&rdquo;</p>
                                        </blockquote>
                                        <div class="author-info">
                                            <div class="author-pic">
                                                <img src="{{ asset('vendor/furni/images/person_3.jpg') }}" alt="Karim B." class="img-fluid">
                                            </div>
                                            <h3 class="font-weight-bold">Karim B.</h3>
                                            <span class="position d-block mb-3">Verified Buyer</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Testimonial Slider -->

<!-- Start Blog Section -->
<div class="blog-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="section-title">Tips &amp; Inspiration</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="post-entry">
                    <span class="post-thumbnail"><img src="{{ asset('vendor/furni/images/post-1.jpg') }}" alt="Image" class="img-fluid"></span>
                    <div class="post-content-entry">
                        <h3>First Time Home Owner Ideas</h3>
                        <div class="meta">
                            <span>Furnishing tips for your first place</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="post-entry">
                    <span class="post-thumbnail"><img src="{{ asset('vendor/furni/images/post-2.jpg') }}" alt="Image" class="img-fluid"></span>
                    <div class="post-content-entry">
                        <h3>How To Keep Your Furniture Clean</h3>
                        <div class="meta">
                            <span>Simple care tips that make pieces last</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                <div class="post-entry">
                    <span class="post-thumbnail"><img src="{{ asset('vendor/furni/images/post-3.jpg') }}" alt="Image" class="img-fluid"></span>
                    <div class="post-content-entry">
                        <h3>Small Space Furniture Ideas</h3>
                        <div class="meta">
                            <span>Make the most of every room</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Blog Section -->
@endsection
