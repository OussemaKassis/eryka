@extends('layouts.app')

@section('hero-title', $article->title)

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <a href="{{ url('/') }}" class="btn btn-sm mb-5">&larr; {{ __('site.back_to_shop') }}</a>

        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <div class="product-thumbnail slider-container" style="height: auto; aspect-ratio: 4 / 5;">
                    @if($article->effective_quantity <= 0)
                        <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.out_of_stock') }}</span>
                    @elseif($article->effective_quantity <= 5)
                        <span class="badge bg-warning text-dark position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.only_x_left', ['qty' => $article->effective_quantity]) }}</span>
                    @endif

                    <div class="slider" id="slider-{{ $article->id }}">
                        @if($article->images->count() > 0)
                            @foreach($article->images as $image)
                                <div class="slide"
                                     style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
                                </div>
                            @endforeach
                        @else
                            <div class="slide slide-placeholder"><i class="fa-solid fa-couch"></i></div>
                        @endif
                    </div>

                    @if($article->images->count() > 1)
                        <button class="slider-arrow prev" type="button" onclick="moveSlide('{{ $article->id }}', -1)">&#10094;</button>
                        <button class="slider-arrow next" type="button" onclick="moveSlide('{{ $article->id }}', 1)">&#10095;</button>
                    @endif
                </div>

                @if($article->images->count() > 1)
                    <div class="thumbnail-strip" id="thumbnail-strip-{{ $article->id }}">
                        @foreach($article->images as $key => $image)
                            <button type="button"
                                    class="thumbnail-item {{ $loop->first ? 'active' : '' }}"
                                    style="background-image: url('{{ asset('storage/' . $image->image_path) }}');"
                                    data-index="{{ $key }}"
                                    aria-label="{{ __('site.image') }} {{ $key + 1 }}"
                                    onclick="goToSlide('{{ $article->id }}', {{ $key }})">
                            </button>
                        @endforeach
                    </div>
                @endif

                @if($article->description)
                    <div class="mt-4">
                        <h3 class="h6 text-black fw-bold mb-1">{{ __('site.description') }}</h3>
                        <p class="mb-0">{{ $article->description }}</p>
                    </div>
                @endif
            </div>

            <div class="col-md-6">
                <p class="mb-1">
                    @if($article->category)
                        <a href="{{ route('shop.category', $article->category->id) }}" class="text-black">{{ $article->category->title }}</a>
                    @else
                        <a href="{{ route('shop.home') }}" class="text-black">{{ __('site.shop') }}</a>
                    @endif
                </p>
                <h1 class="h2 text-black mb-3">{{ $article->title }}</h1>

                @php
                    $hasColors = $article->images->whereNotNull('color')->isNotEmpty();
                    $defaultColorImage = $hasColors ? $article->images->first(fn ($img) => $img->color) : null;
                    $defaultColor = $defaultColorImage->color ?? '';
                    $initialStock = $hasColors ? ($defaultColorImage->quantity ?? 0) : $article->effective_quantity;
                @endphp

                @if($hasColors)
                    <div class="mb-4">
                        <h3 class="h6 text-black mb-2">{{ __('site.color') }}</h3>
                        <div class="d-flex gap-3" id="color-swatches-{{ $article->id }}">
                            @foreach($article->images as $key => $image)
                                @if($image->color)
                                    <button type="button"
                                            class="color-swatch {{ $loop->first ? 'active' : '' }} {{ $image->quantity <= 0 ? 'out-of-stock' : '' }}"
                                            style="background-color: {{ $image->color }};"
                                            title="{{ $image->color }}{{ $image->quantity <= 0 ? ' — ' . __('site.color_out_of_stock') : '' }}"
                                            aria-label="{{ __('site.choose_color', ['color' => $image->color]) }}"
                                            data-index="{{ $key }}"
                                            data-color="{{ $image->color }}"
                                            data-quantity="{{ $image->quantity }}"
                                            onclick="goToSlide('{{ $article->id }}', {{ $key }})">
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <strong class="product-price d-block mb-1" style="font-size: 2rem;">{{ number_format($article->price, 2) }} DT</strong>
                <p class="product-unit-price mb-3">{{ __('site.unit_price') }}</p>

                @if($article->effective_quantity > 0 && $article->effective_quantity <= 5)
                    <span class="badge bg-warning text-dark mb-4">{{ __('site.only_x_left', ['qty' => $article->effective_quantity]) }}</span>
                @elseif($article->effective_quantity > 5)
                    <span class="badge bg-success mb-4">{{ __('site.in_stock') }}</span>
                @endif

                @if($article->effective_quantity > 0)
                    <div class="price-breakdown" id="price-breakdown">
                        <div class="price-breakdown-row">
                            <span>{{ __('site.subtotal') }} (<span data-breakdown-qty>1</span>x)</span>
                            <span data-breakdown-subtotal>{{ number_format($article->price, 2) }} DT</span>
                        </div>
                        <div class="price-breakdown-row">
                            <span>{{ __('site.shipping_fee') }}</span>
                            <span>{{ number_format($shipping, 2) }} DT</span>
                        </div>
                        <div class="price-breakdown-row price-breakdown-total">
                            <span>{{ __('site.total') }}</span>
                            <span data-breakdown-total>{{ number_format($article->price + $shipping, 2) }} DT</span>
                        </div>
                    </div>

                    <form id="add-to-cart-form" action="{{ route('cart.add', $article->id) }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="color" id="selected-color" value="{{ $defaultColor ?? '' }}">

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <label for="quantity" class="text-black mb-0">{{ __('site.quantity') }}</label>
                            @if($hasColors)
                                <span id="color-stock-badge" class="badge {{ $initialStock > 0 ? 'bg-success' : 'bg-secondary' }}">{{ __('site.x_in_stock', ['qty' => $initialStock]) }}</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-end gap-2 flex-wrap">
                            <div class="form-group mb-0" style="max-width: 150px;">
                                <div class="qty-stepper">
                                    <button type="button" class="qty-stepper-btn" data-qty-step="-1" aria-label="{{ __('site.decrease_quantity') }}">&minus;</button>
                                    <input type="number" id="quantity" name="quantity" min="1" value="1" class="form-control text-center" data-stock="{{ $initialStock }}" inputmode="none" autocomplete="off" readonly>
                                    <button type="button" class="qty-stepper-btn" data-qty-step="1" aria-label="{{ __('site.increase_quantity') }}">&plus;</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="add-to-cart-btn" {{ $initialStock <= 0 ? 'disabled' : '' }}>{{ __('site.add_to_cart') }}</button>
                        </div>

                        @if($hasColors)
                            <p id="color-stock-warning" class="text-danger small mt-2 mb-0" style="{{ $initialStock > 0 ? 'display:none;' : '' }}">
                                <i class="fa-solid fa-triangle-exclamation"></i> {{ __('site.requested_qty_unavailable', ['qty' => $initialStock]) }}
                            </p>
                        @endif
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('slider:change', function(e) {
        if (String(e.detail.articleId) !== '{{ $article->id }}') return;

        const thumbnailStrip = document.getElementById('thumbnail-strip-{{ $article->id }}');
        if (thumbnailStrip) {
            thumbnailStrip.querySelectorAll('.thumbnail-item').forEach(function(thumb) {
                thumb.classList.toggle('active', parseInt(thumb.dataset.index, 10) === e.detail.slideIndex);
            });
        }

        const swatchContainer = document.getElementById('color-swatches-{{ $article->id }}');
        if (!swatchContainer) return;

        let matched = null;
        swatchContainer.querySelectorAll('.color-swatch').forEach(function(swatch) {
            const isMatch = parseInt(swatch.dataset.index, 10) === e.detail.slideIndex;
            swatch.classList.toggle('active', isMatch);
            if (isMatch) matched = swatch;
        });

        const color = matched ? matched.dataset.color : '';
        const colorInput = document.getElementById('selected-color');
        if (colorInput) colorInput.value = color;

        if (!matched || matched.dataset.quantity === undefined) return;

        const stock = parseInt(matched.dataset.quantity, 10) || 0;
        const quantityInput = document.getElementById('quantity');
        const stockBadge = document.getElementById('color-stock-badge');
        const stockWarning = document.getElementById('color-stock-warning');
        const addToCartBtn = document.getElementById('add-to-cart-btn');

        if (quantityInput) {
            quantityInput.dataset.stock = stock;
            if (stock <= 0) {
                quantityInput.value = 1;
            } else if (parseInt(quantityInput.value, 10) > stock) {
                quantityInput.value = stock;
            }
            quantityInput.parentElement.querySelectorAll('.qty-stepper-btn').forEach(function(btn) {
                btn.disabled = stock <= 0;
            });
            quantityInput.dispatchEvent(new Event('input', { bubbles: true }));
        }

        if (stockBadge) {
            stockBadge.textContent = @js(__('site.x_in_stock')).replace(':qty', stock);
            stockBadge.classList.toggle('bg-success', stock > 0);
            stockBadge.classList.toggle('bg-secondary', stock <= 0);
        }

        if (stockWarning) stockWarning.style.display = stock <= 0 ? '' : 'none';
        if (addToCartBtn) addToCartBtn.disabled = stock <= 0;
    });

    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const addToCartForm = document.getElementById('add-to-cart-form');
        if (!quantityInput || !addToCartForm) return;

        // Read fresh each time rather than caching once — the selected
        // color's stock (and therefore this cap) can change after a swatch
        // click, updated via quantityInput.dataset.stock.
        function getMaxStock() {
            return parseInt(quantityInput.dataset.stock, 10);
        }
        const price = {{ $article->price }};
        const shippingFee = {{ $shipping }};

        function formatMoney(value) {
            return value.toFixed(2) + ' DT';
        }

        function recalcBreakdown() {
            const qtyEl = document.querySelector('[data-breakdown-qty]');
            const subtotalEl = document.querySelector('[data-breakdown-subtotal]');
            const totalEl = document.querySelector('[data-breakdown-total]');
            if (!qtyEl || !subtotalEl || !totalEl) return;

            let value = parseInt(quantityInput.value, 10);
            if (isNaN(value) || value < 1) value = 1;

            const subtotal = price * value;
            qtyEl.textContent = value;
            subtotalEl.textContent = formatMoney(subtotal);
            totalEl.textContent = formatMoney(subtotal + shippingFee);
        }

        function notifyMaxStock() {
            if (Swal.isVisible()) return;
            Swal.fire({
                icon: 'warning',
                title: @js(__('site.quantity_exceeds_stock_title')),
                text: @js(__('site.quantity_exceeds_stock_text')).replace(':qty', getMaxStock()),
                confirmButtonColor: '#4D5147',
            });
        }

        const allowedKeys = ['ArrowUp', 'ArrowDown', 'Tab', 'Escape', 'Enter'];
        quantityInput.addEventListener('keydown', function(e) {
            if (!allowedKeys.includes(e.key)) {
                e.preventDefault();
            }
        });

        quantityInput.addEventListener('paste', function(e) {
            e.preventDefault();
        });

        quantityInput.addEventListener('input', function() {
            const value = parseInt(quantityInput.value, 10);
            const max = getMaxStock();
            if (!isNaN(value) && max > 0 && value > max) {
                quantityInput.value = max;
                notifyMaxStock();
            }
            recalcBreakdown();
        });

        recalcBreakdown();

        document.querySelectorAll('[data-qty-step]').forEach(function(button) {
            button.addEventListener('click', function() {
                const step = parseInt(button.dataset.qtyStep, 10);
                let value = parseInt(quantityInput.value, 10);
                if (isNaN(value)) value = 1;

                value += step;
                if (value < 1) value = 1;

                const max = getMaxStock();
                if (max > 0 && value > max) {
                    value = max;
                    notifyMaxStock();
                }

                quantityInput.value = value;
                quantityInput.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });

        addToCartForm.addEventListener('submit', function(e) {
            const max = getMaxStock();

            if (max <= 0) {
                e.preventDefault();
                return;
            }

            const value = parseInt(quantityInput.value, 10);

            if (isNaN(value) || value < 1) {
                e.preventDefault();
                quantityInput.value = 1;
                return;
            }

            if (value > max) {
                e.preventDefault();
                quantityInput.value = max;
                notifyMaxStock();
            }
        });
    });
</script>
@endpush
