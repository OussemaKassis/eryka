<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/favicon/favicon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/favicon-180.png') }}">

    <title>{{ config('app.name', 'Shop') }}</title>

    <link href="{{ asset('vendor/furni/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/furni/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/furni/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}" rel="stylesheet">
</head>
<body>

    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark sticky-top" aria-label="{{ config('app.name') }} navigation bar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name', 'Shop') }}" class="navbar-brand-logo">
            </a>

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
                    <li class="nav-item {{ request()->routeIs('shop.actualite') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.actualite') }}">{{ __('site.nav_actualite') }}</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.about') }}">{{ __('site.nav_about') }}</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop.contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop.contact') }}">{{ __('site.nav_contact') }}</a>
                    </li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    {{-- Language switcher hidden for now — French only. --}}
                    <li>
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}" title="{{ __('site.nav_cart') }}" aria-label="{{ __('site.nav_cart') }}">
                            <img src="{{ asset('vendor/furni/images/cart.svg') }}" alt="{{ __('site.nav_cart') }}">
                            @if(count(session('cart', [])) > 0)
                                <span class="badge rounded-pill cart-badge position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
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
    @if(request()->routeIs('shop.home') || request()->routeIs('shop.products'))
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
                            @if($pageHero?->image_path)
                                <div class="hero-img-wrap">
                                    <img src="{{ asset('storage/' . $pageHero->image_path) }}" alt="{{ config('app.name', 'Shop') }}" class="img-fluid">
                                </div>
                            @else
                                @hasSection('hero-image')
                                    <div class="hero-img-wrap">
                                        @yield('hero-image')
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <!-- End Hero Section -->

    <main>
        @yield('content')
    </main>

    <!-- Start Footer Section -->
    <footer class="footer-section footer-section-branded">
        <div class="container relative">
            <div class="row g-5 mb-5">
                <div class="col-lg-5">
                    <div class="mb-4 footer-logo-wrap">
                        <a href="{{ url('/') }}" class="footer-logo">
                            <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name', 'Shop') }}" class="footer-logo-img">
                        </a>
                    </div>
                    <p class="mb-4">{{ $siteSettings->footer_tagline ?: __('site.footer_tagline') }}</p>

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
                        <li><a href="{{ route('shop.actualite') }}">{{ __('site.nav_actualite') }}</a></li>
                        <li><a href="{{ route('shop.about') }}">{{ __('site.nav_about') }}</a></li>
                        <li><a href="{{ route('shop.contact') }}">{{ __('site.nav_contact') }}</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-4">
                    <h3 class="footer-heading">{{ __('site.footer_shop') }}</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('cart.index') }}">{{ __('site.footer_your_cart') }}</a></li>
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

    <button type="button" id="back-to-top" class="back-to-top-btn" aria-label="{{ __('site.back_to_top') }}">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <script src="{{ asset('vendor/furni/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/furni/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('vendor/furni/js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const sliders = {};

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success') || session('error'))
                Swal.fire({
                    position: 'center',
                    icon: @js(session('success') ? 'success' : 'error'),
                    title: @js(session('success') ?? session('error')),
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                });
            @endif

            document.querySelectorAll('.slider').forEach(slider => {
                const articleId = slider.id.split('-')[1];
                sliders[articleId] = {
                    currentSlide: 0,
                    totalSlides: slider.children.length
                };
            });

            // The header is fixed (always visible, even mid-scroll) rather than
            // sticky, so it's removed from document flow — push the page content
            // down by its real rendered height to avoid it overlapping the hero.
            const navbar = document.querySelector('.custom-navbar');
            if (navbar) {
                const setNavbarOffset = () => {
                    document.body.style.paddingTop = navbar.offsetHeight + 'px';
                };
                setNavbarOffset();
                window.addEventListener('resize', setNavbarOffset);
            }

            const backToTopBtn = document.getElementById('back-to-top');
            if (backToTopBtn) {
                function toggleBackToTop() {
                    backToTopBtn.classList.toggle('is-visible', window.scrollY > 400);
                }

                window.addEventListener('scroll', toggleBackToTop);
                toggleBackToTop();

                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
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

            document.dispatchEvent(new CustomEvent('slider:change', {
                detail: { articleId: articleId, slideIndex: slider.currentSlide }
            }));
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
