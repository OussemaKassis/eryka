@extends('layouts.app')

@section('hero-title', __('site.your_cart'))

@section('content')
<div id="cart-page" class="untree_co-section before-footer-section">
    <div class="container">
        @if($items->isEmpty())
            <div class="row">
                <div class="col-md-12 text-center cart-empty-state">
                    <div class="cart-empty-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <h2 class="h3 mb-3 text-black">{{ __('site.cart_empty') }}</h2>
                    <p class="mb-4">{{ __('site.cart_empty_desc') }}</p>
                    <a href="{{ route('shop.products') }}" class="btn btn-primary">{{ __('site.continue_shopping') }}</a>
                </div>
            </div>
        @else
            <div class="row mb-5">
                <div class="col-md-12">
                    <!-- Desktop/tablet: full table -->
                    <div class="site-blocks-table d-none d-md-block">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">{{ __('site.image') }}</th>
                                    <th class="product-name">{{ __('site.product') }}</th>
                                    <th>{{ __('site.color') }}</th>
                                    <th>{{ __('site.price') }}</th>
                                    <th>{{ __('site.quantity') }}</th>
                                    <th>{{ __('site.total') }}</th>
                                    <th>{{ __('site.remove') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr data-cart-row data-cart-key="{{ $item['key'] }}" data-price="{{ $item['article']->price }}">
                                        <td class="product-thumbnail">
                                            @if($item['article']->images->first())
                                                <img src="{{ asset('storage/' . $item['article']->images->first()->image_path) }}" alt="Image" class="img-fluid">
                                            @endif
                                        </td>
                                        <td class="product-name">
                                            <a href="{{ route('shop.product', $item['article']->id) }}" class="h5 text-black">{{ $item['article']->title }}</a>
                                        </td>
                                        <td>
                                            @if($item['color'])
                                                <span class="color-swatch color-swatch-sm" style="background-color: {{ $item['color'] }};" title="{{ $item['color'] }}"></span>
                                            @else
                                                &mdash;
                                            @endif
                                        </td>
                                        <td>{{ number_format($item['article']->price, 2) }} DT</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item['key']) }}" method="POST" class="cart-update-form d-flex align-items-center justify-content-center gap-2">
                                                @csrf
                                                <div class="qty-stepper">
                                                    <button type="button" class="qty-stepper-btn" data-qty-step="-1" aria-label="{{ __('site.decrease_quantity') }}">&minus;</button>
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control text-center" data-cart-quantity data-stock="{{ $item['article']->quantityForColor($item['color']) }}" inputmode="none" autocomplete="off" readonly>
                                                    <button type="button" class="qty-stepper-btn" data-qty-step="1" aria-label="{{ __('site.increase_quantity') }}">&plus;</button>
                                                </div>
                                                <noscript><button type="submit" class="btn btn-sm">{{ __('site.update') }}</button></noscript>
                                            </form>
                                        </td>
                                        <td data-cart-subtotal>{{ number_format($item['subtotal'], 2) }} DT</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item['key']) }}" method="POST" class="cart-remove-form">
                                                @csrf
                                                <button type="submit" class="cart-remove-btn" aria-label="{{ __('site.remove') }}">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile: one card per item, no horizontal scrolling -->
                    <div class="cart-mobile-list d-md-none">
                        @foreach($items as $item)
                            <div class="cart-mobile-item" data-cart-row data-cart-key="{{ $item['key'] }}" data-price="{{ $item['article']->price }}">
                                <div class="cart-mobile-item-top">
                                    <div class="cart-mobile-thumb">
                                        @if($item['article']->images->first())
                                            <img src="{{ asset('storage/' . $item['article']->images->first()->image_path) }}" alt="Image" class="img-fluid">
                                        @endif
                                    </div>
                                    <div class="cart-mobile-info">
                                        <a href="{{ route('shop.product', $item['article']->id) }}" class="cart-mobile-title">{{ $item['article']->title }}</a>
                                        @if($item['color'])
                                            <span class="color-swatch color-swatch-sm" style="background-color: {{ $item['color'] }};" title="{{ $item['color'] }}"></span>
                                        @endif
                                        <div class="cart-mobile-price">{{ number_format($item['article']->price, 2) }} DT</div>
                                    </div>
                                </div>

                                <div class="cart-mobile-row">
                                    <span class="cart-mobile-label">{{ __('site.quantity') }}</span>
                                    <form action="{{ route('cart.update', $item['key']) }}" method="POST" class="cart-update-form d-flex align-items-center justify-content-center gap-2">
                                        @csrf
                                        <div class="qty-stepper">
                                            <button type="button" class="qty-stepper-btn" data-qty-step="-1" aria-label="{{ __('site.decrease_quantity') }}">&minus;</button>
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control text-center" data-cart-quantity data-stock="{{ $item['article']->quantityForColor($item['color']) }}" inputmode="none" autocomplete="off" readonly>
                                            <button type="button" class="qty-stepper-btn" data-qty-step="1" aria-label="{{ __('site.increase_quantity') }}">&plus;</button>
                                        </div>
                                        <noscript><button type="submit" class="btn btn-sm">{{ __('site.update') }}</button></noscript>
                                    </form>
                                </div>

                                <div class="cart-mobile-row">
                                    <span class="cart-mobile-label">{{ __('site.subtotal') }}</span>
                                    <strong data-cart-subtotal>{{ number_format($item['subtotal'], 2) }} DT</strong>
                                </div>

                                <form action="{{ route('cart.remove', $item['key']) }}" method="POST" class="cart-remove-form text-center">
                                    @csrf
                                    <button type="submit" class="cart-remove-btn" aria-label="{{ __('site.remove') }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="price-breakdown">
                        <div class="price-breakdown-row">
                            <span>{{ __('site.subtotal') }}</span>
                            <span data-cart-subtotal-total>{{ number_format($subtotal, 2) }} DT</span>
                        </div>
                        <div class="price-breakdown-row">
                            <span>{{ __('site.shipping_fee') }} <small class="price-breakdown-note">({{ __('site.shipping_cod_note') }})</small></span>
                            <span>{{ number_format($shipping, 2) }} DT</span>
                        </div>
                        <div class="price-breakdown-row price-breakdown-total">
                            <span>{{ __('site.total') }}</span>
                            <span data-cart-total>{{ number_format($total, 2) }} DT</span>
                        </div>
                    </div>
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-lg py-3 w-100">{{ __('site.proceed_to_checkout') }}</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('[data-cart-row]');
        const totalEl = document.querySelector('[data-cart-total]');
        const subtotalTotalEl = document.querySelector('[data-cart-subtotal-total]');
        const shippingFee = {{ $shipping }};
        let submitTimers = new WeakMap();

        // Each cart line is rendered twice — once in the desktop table, once
        // in the mobile card list — shown/hidden by breakpoint. Group them
        // by cart key so the total is computed once per item (not once per
        // representation) and both stay visually in sync.
        const rowsByKey = new Map();
        rows.forEach(row => {
            const key = row.dataset.cartKey;
            if (!rowsByKey.has(key)) rowsByKey.set(key, []);
            rowsByKey.get(key).push(row);
        });

        function formatMoney(value) {
            return value.toFixed(2) + ' DT';
        }

        function notifyMaxStock(maxStock) {
            if (Swal.isVisible()) return;
            Swal.fire({
                icon: 'warning',
                title: @js(__('site.quantity_exceeds_stock_title')),
                text: @js(__('site.quantity_exceeds_stock_text')).replace(':qty', maxStock),
                confirmButtonColor: '#4D5147',
            });
        }

        document.querySelectorAll('.cart-remove-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: @js(__('site.confirm_remove_title')),
                    text: @js(__('site.confirm_remove_text')),
                    showCancelButton: true,
                    confirmButtonText: @js(__('site.confirm_remove_yes')),
                    cancelButtonText: @js(__('site.cancel')),
                    confirmButtonColor: '#A8503F',
                    cancelButtonColor: '#9C8C7C',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        function recalculate() {
            let grandTotal = 0;

            rowsByKey.forEach(rowGroup => {
                const primary = rowGroup[0];
                const price = parseFloat(primary.dataset.price);
                const primaryInput = primary.querySelector('[data-cart-quantity]');
                const max = parseInt(primaryInput.dataset.stock, 10);
                let quantity = parseInt(primaryInput.value, 10);

                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                }
                if (!isNaN(max) && quantity > max) {
                    quantity = max;
                }

                const subtotal = price * quantity;

                rowGroup.forEach(row => {
                    const quantityInput = row.querySelector('[data-cart-quantity]');
                    const subtotalEl = row.querySelector('[data-cart-subtotal]');
                    if (parseInt(quantityInput.value, 10) !== quantity) quantityInput.value = quantity;
                    if (subtotalEl) subtotalEl.textContent = formatMoney(subtotal);
                });

                grandTotal += subtotal;
            });

            if (subtotalTotalEl) subtotalTotalEl.textContent = formatMoney(grandTotal);
            totalEl.textContent = formatMoney(grandTotal + shippingFee);
        }

        const allowedKeys = ['ArrowUp', 'ArrowDown', 'Tab', 'Escape', 'Enter'];

        rows.forEach(row => {
            const quantityInput = row.querySelector('[data-cart-quantity]');
            const form = row.querySelector('.cart-update-form');
            const maxStock = parseInt(quantityInput.dataset.stock, 10);

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
                if (!isNaN(value) && !isNaN(maxStock) && value > maxStock) {
                    notifyMaxStock(maxStock);
                }

                // This row is the one the user actually edited — push its
                // value into the other representation of the same item
                // (desktop row vs. mobile card) before recalculating, so
                // recalculate() has a single, already-agreed-on quantity
                // to read per item.
                (rowsByKey.get(row.dataset.cartKey) || []).forEach(sibling => {
                    if (sibling === row) return;
                    const siblingInput = sibling.querySelector('[data-cart-quantity]');
                    if (siblingInput.value !== quantityInput.value) siblingInput.value = quantityInput.value;
                });

                recalculate();

                clearTimeout(submitTimers.get(quantityInput));
                submitTimers.set(quantityInput, setTimeout(function() {
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    }).catch(function() {});
                }, 600));
            });

            row.querySelectorAll('[data-qty-step]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const step = parseInt(button.dataset.qtyStep, 10);
                    let value = parseInt(quantityInput.value, 10);
                    if (isNaN(value)) value = 1;

                    value += step;
                    if (value < 1) value = 1;

                    quantityInput.value = value;
                    quantityInput.dispatchEvent(new Event('input', { bubbles: true }));
                });
            });
        });
    });
</script>
@endpush
