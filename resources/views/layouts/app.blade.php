<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('vendor/furni/favicon.png') }}">

    <title>{{ config('app.name', 'Shop') }}</title>

    <link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/furni/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/furni/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="{{ config('app.name') }} navigation bar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Shop') }}<span>.</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsShop" aria-controls="navbarsShop" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsShop">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item {{ request()->routeIs('shop.home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.home') }}">Home</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.products') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.products') }}">Products</a>
                    </li>
                    <li class="nav-item dropdown {{ request()->routeIs('shop.category') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            @forelse($navFamilies as $family)
                                <li><a class="dropdown-item fw-bold" href="{{ route('shop.category', $family->id) }}">{{ $family->title }}</a></li>
                                @foreach($family->children as $child)
                                    <li><a class="dropdown-item ps-4" href="{{ route('shop.category', $child->id) }}">{{ $child->title }}</a></li>
                                @endforeach
                            @empty
                                <li><span class="dropdown-item text-muted">No categories yet</span></li>
                            @endforelse
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.about') }}">About us</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.contact') }}">Contact us</a>
                    </li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li>
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}" title="Cart" aria-label="Cart">
                            <img src="{{ asset('vendor/furni/images/cart.svg') }}" alt="Cart">
                            @if(count(session('cart', [])) > 0)
                                <span class="badge rounded-pill bg-secondary position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('/admin') }}" title="Admin login" aria-label="Admin login">
                            <img src="{{ asset('vendor/furni/images/user.svg') }}" alt="Admin login">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Header/Navigation -->

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>@yield('hero-title', config('app.name', 'Shop'))</h1>
                        @hasSection('hero-subtitle')
                            <p class="mb-4">@yield('hero-subtitle')</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                    @hasSection('hero-image')
                        <div class="hero-img-wrap">
                            @yield('hero-image')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <main>
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-4">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Start Footer Section -->
    <footer class="footer-section footer-section-branded">
        <div class="container relative">
            <div class="row g-5 mb-5">
                <div class="col-lg-5">
                    <div class="mb-4 footer-logo-wrap"><a href="{{ url('/') }}" class="footer-logo">{{ config('app.name', 'Shop') }}<span>.</span></a></div>
                    <p class="mb-0">Quality furniture and home essentials, delivered straight to your door.</p>
                </div>

                <div class="col-6 col-lg-3">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.home') }}">Home</a></li>
                        <li><a href="{{ route('shop.products') }}">Products</a></li>
                        <li><a href="{{ route('shop.about') }}">About us</a></li>
                        <li><a href="{{ route('shop.contact') }}">Contact us</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-4">
                    <h3 class="footer-heading">Shop</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('cart.index') }}">Your Cart</a></li>
                        <li><a href="{{ url('/admin') }}">Admin Login</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-top copyright">
                <div class="row pt-4">
                    <div class="col-lg-12">
                        <p class="mb-2 text-center">Copyright &copy;{{ date('Y') }} {{ config('app.name', 'Shop') }}. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer Section -->

    <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/furni/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('vendor/furni/js/custom.js') }}"></script>

    <script>
        const sliders = {};

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.slider').forEach(slider => {
                const articleId = slider.id.split('-')[1];
                sliders[articleId] = {
                    currentSlide: 0,
                    totalSlides: slider.children.length
                };
            });
        });

        function moveSlide(articleId, direction) {
            const slider = sliders[articleId];
            slider.currentSlide = (slider.currentSlide + direction + slider.totalSlides) % slider.totalSlides;
            updateSlider(articleId);
        }

        function goToSlide(articleId, slideIndex) {
            sliders[articleId].currentSlide = slideIndex;
            updateSlider(articleId);
        }

        function updateSlider(articleId) {
            const slider = sliders[articleId];
            const sliderElement = document.getElementById(`slider-${articleId}`);
            const dots = document.querySelectorAll(`#slider-nav-${articleId} .slider-dot`);

            sliderElement.style.transform = `translateX(-${slider.currentSlide * 100}%)`;

            dots.forEach((dot, index) => {
                if (index === slider.currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
