@extends('layouts.app')

@section('hero-title', $article->title)

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <a href="{{ url('/') }}" class="btn btn-sm mb-5">&larr; {{ __('site.back_to_shop') }}</a>

        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <div class="p-3 p-lg-4 border bg-white">
                    <div class="product-thumbnail slider-container" style="height: auto; aspect-ratio: 4 / 5;">
                        @if($article->quantity <= 0)
                            <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">Out of Stock</span>
                        @elseif($article->quantity <= 5)
                            <span class="badge bg-warning text-dark position-absolute" style="top: 10px; left: 10px; z-index: 11;">Only {{ $article->quantity }} left</span>
                        @endif

                        <div class="slider" id="slider-{{ $article->id }}">
                            @if($article->images->count() > 0)
                                @foreach($article->images as $image)
                                    <div class="slide"
                                         style="background-image: url('{{ asset('storage/' . $image->image_path) }}')">
                                    </div>
                                @endforeach
                            @else
                                <div class="slide" style="background: #E3D9C8;"></div>
                            @endif
                        </div>

                        @if($article->images->count() > 1)
                            <button class="slider-arrow prev" type="button" onclick="moveSlide('{{ $article->id }}', -1)">&#10094;</button>
                            <button class="slider-arrow next" type="button" onclick="moveSlide('{{ $article->id }}', 1)">&#10095;</button>
                            <div class="slider-nav" id="slider-nav-{{ $article->id }}">
                                @foreach($article->images as $key => $image)
                                    <span class="slider-dot {{ $loop->first ? 'active' : '' }}"
                                          onclick="goToSlide('{{ $article->id }}', {{ $key }})"></span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <p class="mt-4 mb-0 text-black">{{ $article->description }}</p>
                    <strong class="product-price d-block mt-3" style="font-size: 1.5rem;">{{ number_format($article->price, 2) }} DT</strong>
                </div>
            </div>

            <div class="col-md-6">
                <h2 class="h3 mb-3 text-black">{{ __('site.order_details') }}</h2>
                <div class="p-3 p-lg-4 border bg-white">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="order-form" action="{{ route('shop.order.submit', $article->id) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="customer_first_name" class="text-black">{{ __('site.first_name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="customer_first_name" name="customer_first_name" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="customer_last_name" class="text-black">{{ __('site.last_name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="customer_last_name" name="customer_last_name" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="city" class="text-black">{{ __('site.city') }} <span class="text-danger">*</span></label>
                                <input type="text" id="city" name="city" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="text-black">{{ __('site.email') }} <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="phone_number" class="text-black">{{ __('site.phone_number') }} <span class="text-danger">*</span></label>
                                <input type="text" id="phone_number" name="phone_number" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="text-black">{{ __('site.address') }} <span class="text-danger">*</span></label>
                            <textarea id="address" name="address" required class="form-control" rows="3"></textarea>
                        </div>

                        @if($article->quantity > 0)
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
                        @endif

                        <div class="d-flex align-items-end gap-2 mt-3 flex-wrap">
                            <div class="form-group mb-0" style="max-width: 150px;">
                                <label for="quantity" class="text-black">{{ __('site.quantity') }} <span class="text-danger">*</span></label>
                                <div class="qty-stepper">
                                    <button type="button" class="qty-stepper-btn" data-qty-step="-1" aria-label="{{ __('site.decrease_quantity') }}">&minus;</button>
                                    <input type="number" id="quantity" name="quantity" min="1" max="{{ $article->quantity }}" value="1" required class="form-control text-center" data-stock="{{ $article->quantity }}" inputmode="none" autocomplete="off" {{ $article->quantity <= 0 ? 'disabled' : '' }}>
                                    <button type="button" class="qty-stepper-btn" data-qty-step="1" aria-label="{{ __('site.increase_quantity') }}">&plus;</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg py-3 flex-grow-1" {{ $article->quantity <= 0 ? 'disabled' : '' }}>
                                {{ $article->quantity <= 0 ? __('site.out_of_stock') : __('site.submit_order') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        if (!quantityInput) return;

        const maxStock = parseInt(quantityInput.dataset.stock, 10);
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
                text: @js(__('site.quantity_exceeds_stock_text')).replace(':qty', maxStock),
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
            if (!isNaN(value) && value > maxStock) {
                quantityInput.value = maxStock;
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
                if (value > maxStock) {
                    value = maxStock;
                    notifyMaxStock();
                }

                quantityInput.value = value;
                quantityInput.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });

        const orderForm = document.getElementById('order-form');
        if (orderForm) {
            orderForm.addEventListener('submit', function(e) {
                const value = parseInt(quantityInput.value, 10);

                if (isNaN(value) || value < 1) {
                    e.preventDefault();
                    quantityInput.value = 1;
                    return;
                }

                if (value > maxStock) {
                    e.preventDefault();
                    quantityInput.value = maxStock;
                    notifyMaxStock();
                }
            });
        }
    });
</script>
@endpush
@endsection
