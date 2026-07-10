@extends('layouts.app')

@section('hero-title', $article->title)

@section('content')
<div id="product-detail" class="untree_co-section before-footer-section">
    <div class="container">
        <a href="{{ route('shop.products') }}" class="btn btn-primary btn-sm mb-5">&larr; {{ __('site.back_to_shop') }}</a>

        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <div class="product-thumbnail slider-container" style="height: auto; aspect-ratio: 4 / 5;">
                    @if($article->effective_quantity <= 0)
                        <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.out_of_stock') }}</span>
                    @endif

                    <div class="slider" id="slider-{{ $article->id }}">
                        @if($article->images->count() > 0)
                            @foreach($article->images as $image)
                                <div class="slide">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $article->title }}"
                                         @if($loop->first) fetchpriority="high" @else loading="lazy" @endif
                                         decoding="async">
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

                @if($article->detail)
                    <div class="mt-4">
                        <h3 class="h6 text-black fw-bold mb-1">{{ __('site.description') }}</h3>
                        <div class="mb-0">{!! $article->detail !!}</div>
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

                <span id="stock-status-badge" class="badge {{ $initialStock > 0 ? 'bg-success' : 'bg-danger' }} mb-4">{{ $initialStock > 0 ? __('site.in_stock') : __('site.out_of_stock') }}</span>

                @if($article->effective_quantity > 0)
                    <div class="price-breakdown" id="price-breakdown">
                        <div class="price-breakdown-row">
                            <span>{{ __('site.subtotal') }} (x<span data-breakdown-qty>1</span>)</span>
                            <span data-breakdown-subtotal>{{ number_format($article->price, 2) }} DT</span>
                        </div>
                        <div class="price-breakdown-row">
                            <span>{{ __('site.shipping_fee') }} <small class="price-breakdown-note">({{ __('site.shipping_cod_note') }})</small></span>
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
                                <span id="color-stock-badge" class="badge bg-secondary {{ $initialStock > 0 ? 'd-none' : '' }}">{{ __('site.x_in_stock', ['qty' => $initialStock]) }}</span>
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

<div class="modal fade" id="cartConfirmModal" tabindex="-1" aria-labelledby="cartConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cart-confirm-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="cartConfirmModalLabel">{{ __('site.cart_confirm_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cart-confirm-actions">
                    <button type="button" class="btn cart-confirm-continue-btn" data-bs-dismiss="modal">{{ __('site.cart_confirm_continue') }}</button>
                    <a href="{{ route('cart.checkout') }}" class="btn cart-confirm-buy-now-btn">
                        <i class="fa-solid fa-bag-shopping"></i> {{ __('site.cart_confirm_buy_now') }}
                    </a>
                </div>

                <div class="cart-confirm-product">
                    <img id="cartConfirmImage" src="" alt="" class="cart-confirm-product-img">
                    <div class="cart-confirm-product-info">
                        <h6 id="cartConfirmTitle" class="mb-1"></h6>
                        <p id="cartConfirmDesc" class="small text-muted mb-2"></p>
                        <div class="d-flex gap-2 flex-wrap">
                            <span id="cartConfirmQtyBadge" class="badge cart-confirm-badge"></span>
                            <span id="cartConfirmColorBadge" class="badge cart-confirm-badge"></span>
                        </div>
                    </div>
                </div>

                <div class="cart-confirm-summary">
                    <p id="cartConfirmCount" class="fw-bold mb-3"></p>
                    <div class="cart-confirm-summary-row">
                        <span>{{ __('site.cart_confirm_total_products') }}</span>
                        <span id="cartConfirmSubtotal"></span>
                    </div>
                    <div class="cart-confirm-summary-row">
                        <span>{{ __('site.shipping_fee') }}</span>
                        <span id="cartConfirmShipping"></span>
                    </div>
                    <div class="cart-confirm-summary-row cart-confirm-summary-total">
                        <span>{{ __('site.total') }}</span>
                        <span id="cartConfirmTotal"></span>
                    </div>
                </div>
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
            stockBadge.classList.toggle('d-none', stock > 0);
        }

        if (stockWarning) stockWarning.style.display = stock <= 0 ? '' : 'none';
        if (addToCartBtn) addToCartBtn.disabled = stock <= 0;

        const statusBadge = document.getElementById('stock-status-badge');
        if (statusBadge) {
            statusBadge.textContent = stock > 0 ? @js(__('site.in_stock')) : @js(__('site.out_of_stock'));
            statusBadge.classList.toggle('bg-success', stock > 0);
            statusBadge.classList.toggle('bg-danger', stock <= 0);
        }
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
            return parseFloat(value).toFixed(2) + ' DT';
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

        function showCartConfirmModal(data) {
            const image = document.getElementById('cartConfirmImage');
            if (data.article.image) {
                image.src = data.article.image;
                image.style.display = '';
            } else {
                image.style.display = 'none';
            }

            document.getElementById('cartConfirmTitle').textContent =
                data.article.title + ' : ' + formatMoney(data.article.price);
            document.getElementById('cartConfirmDesc').textContent = data.article.description || '';

            document.getElementById('cartConfirmQtyBadge').textContent =
                @js(__('site.quantity')) + ': ' + data.quantity;

            const colorBadge = document.getElementById('cartConfirmColorBadge');
            if (data.color) {
                colorBadge.style.display = '';
                colorBadge.innerHTML = @js(__('site.color')) + ': <span class="cart-confirm-color-dot" style="background-color: ' + data.color + ';"></span>';
            } else {
                colorBadge.style.display = 'none';
            }

            document.getElementById('cartConfirmCount').textContent = data.cart.countLabel;
            document.getElementById('cartConfirmSubtotal').textContent = formatMoney(data.cart.subtotal);
            document.getElementById('cartConfirmShipping').textContent = formatMoney(data.cart.shipping);
            document.getElementById('cartConfirmTotal').textContent = formatMoney(data.cart.total);

            bootstrap.Modal.getOrCreateInstance(document.getElementById('cartConfirmModal')).show();
        }

        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const max = getMaxStock();
            if (max <= 0) return;

            const value = parseInt(quantityInput.value, 10);

            if (isNaN(value) || value < 1) {
                quantityInput.value = 1;
                return;
            }

            if (value > max) {
                quantityInput.value = max;
                notifyMaxStock();
                return;
            }

            fetch(addToCartForm.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: new FormData(addToCartForm),
            })
                .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
                .then(function(result) {
                    if (!result.ok || !result.data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: (result.data && result.data.message) || @js(__('site.flash_error_title')),
                            confirmButtonColor: '#4D5147',
                        });
                        return;
                    }
                    showCartConfirmModal(result.data);
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: @js(__('site.flash_error_title')),
                        confirmButtonColor: '#4D5147',
                    });
                });
        });
    });
</script>
@endpush
