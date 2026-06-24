<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('vendor/furni/favicon.png') }}">

    <title>{{ config('app.name', 'Shop') }}</title>

    <link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700;800&display=swap" rel="stylesheet">
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
                        <a class="nav-link" href="{{ route('shop.home') }}">{{ __('site.nav_home') }}</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.products') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.products') }}">{{ __('site.nav_products') }}</a>
                    </li>
                    {{-- Categories dropdown hidden from nav per request; routes/controller/views untouched. --}}
                    <li class="nav-item {{ request()->routeIs('shop.about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.about') }}">{{ __('site.nav_about') }}</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.contact') }}">{{ __('site.nav_contact') }}</a>
                    </li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="langSwitcher" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langSwitcher">
                            <li><a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}" href="{{ route('lang.switch', 'fr') }}">Français</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}" title="{{ __('site.nav_cart') }}" aria-label="{{ __('site.nav_cart') }}">
                            <img src="{{ asset('vendor/furni/images/cart.svg') }}" alt="{{ __('site.nav_cart') }}">
                            @if(count(session('cart', [])) > 0)
                                <span class="badge rounded-pill bg-secondary position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Header/Navigation -->

    <!-- Start Hero Section -->
    @hasSection('hero-bg-slides')
        <div class="hero hero-bg-slider-mode" id="hero-slider">
            <div class="hero-slider-track">
                @yield('hero-bg-slides')
            </div>
            <div class="hero-overlay"></div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-9">
                        <div class="intro-excerpt">
                            <h1>@yield('hero-title', config('app.name', 'Shop'))</h1>
                            @hasSection('hero-subtitle')
                                <p class="mb-4">@yield('hero-subtitle')</p>
                            @endif
                            @unless(request()->routeIs('shop.products'))
                                <a href="{{ route('shop.products') }}" class="btn btn-secondary">{{ __('site.hero_shop_now') }}</a>
                            @endunless
                        </div>
                    </div>
                </div>
            </div>

            @yield('hero-slider-dots')
        </div>
    @else
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
    @endif
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
                    <p class="mb-4">{{ __('site.footer_tagline') }}</p>

                    @if(isset($socialLinks) && $socialLinks->isNotEmpty())
                        <ul class="list-unstyled custom-social">
                            @foreach($socialLinks as $link)
                                <li>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($link->platform) }}">
                                        <i class="{{ $link->icon_class }}"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="col-6 col-lg-3">
                    <h3 class="footer-heading">{{ __('site.footer_quick_links') }}</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.home') }}">{{ __('site.nav_home') }}</a></li>
                        <li><a href="{{ route('shop.products') }}">{{ __('site.nav_products') }}</a></li>
                        <li><a href="{{ route('shop.about') }}">{{ __('site.nav_about') }}</a></li>
                        <li><a href="{{ route('shop.contact') }}">{{ __('site.nav_contact') }}</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-4">
                    <h3 class="footer-heading">{{ __('site.footer_shop') }}</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('cart.index') }}">{{ __('site.footer_your_cart') }}</a></li>
                        <li><a href="{{ url('/admin') }}">{{ __('site.footer_admin_login') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-top copyright">
                <div class="row pt-4">
                    <div class="col-lg-12">
                        <p class="mb-2 text-center">{{ __('site.footer_copyright', ['year' => date('Y'), 'name' => config('app.name', 'Shop')]) }}</p>
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

        (function() {
            var track = document.querySelector('#hero-slider .hero-slider-track');
            if (!track) return;
            var slides = track.children;
            var dots = document.querySelectorAll('#hero-slider .hero-dot');
            var current = 0;
            var intervalId;

            function show(index) {
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
                dots.forEach(function(dot, i) { dot.classList.toggle('active', i === index); });
                current = index;
            }

            function resetInterval() {
                clearInterval(intervalId);
                if (slides.length > 1) {
                    intervalId = setInterval(function() {
                        show((current + 1) % slides.length);
                    }, 3500);
                }
            }

            window.goToHeroSlide = function(index) {
                show(index);
                resetInterval();
            };

            resetInterval();
        })();
    </script>

    @stack('scripts')
</body>
</html>
