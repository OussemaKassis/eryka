@extends('layouts.app')

@section('hero-title', __('site.your_cart'))

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($items->isEmpty())
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-4">{{ __('site.cart_empty') }}</p>
                    <a href="{{ route('shop.home') }}" class="btn btn-primary">{{ __('site.continue_shopping') }}</a>
                </div>
            </div>
        @else
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="site-blocks-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">{{ __('site.image') }}</th>
                                    <th class="product-name">{{ __('site.product') }}</th>
                                    <th>{{ __('site.price') }}</th>
                                    <th>{{ __('site.quantity') }}</th>
                                    <th>{{ __('site.total') }}</th>
                                    <th>{{ __('site.remove') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr data-cart-row data-price="{{ $item['article']->price }}">
                                        <td class="product-thumbnail">
                                            @if($item['article']->images->first())
                                                <img src="{{ asset('storage/' . $item['article']->images->first()->image_path) }}" alt="Image" class="img-fluid">
                                            @endif
                                        </td>
                                        <td class="product-name">
                                            <a href="{{ route('shop.product', $item['article']->id) }}" class="h5 text-black">{{ $item['article']->title }}</a>
                                        </td>
                                        <td>${{ number_format($item['article']->price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item['article']->id) }}" method="POST" class="cart-update-form d-flex align-items-center justify-content-center gap-2">
                                                @csrf
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['article']->quantity }}" class="form-control text-center" style="max-width: 80px;" data-cart-quantity>
                                                <noscript><button type="submit" class="btn btn-sm">{{ __('site.update') }}</button></noscript>
                                            </form>
                                        </td>
                                        <td data-cart-subtotal>${{ number_format($item['subtotal'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item['article']->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm">&times;</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="p-3 p-lg-4 border bg-white">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <span class="text-black">{{ __('site.total') }}</span>
                            </div>
                            <div class="col-md-6 text-end">
                                <strong class="text-black" data-cart-total>${{ number_format($total, 2) }}</strong>
                            </div>
                        </div>
                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-lg py-3 w-100">{{ __('site.proceed_to_checkout') }}</a>
                    </div>
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
        let submitTimers = new WeakMap();

        function formatMoney(value) {
            return '$' + value.toFixed(2);
        }

        function recalculate() {
            let grandTotal = 0;

            rows.forEach(row => {
                const price = parseFloat(row.dataset.price);
                const quantityInput = row.querySelector('[data-cart-quantity]');
                const subtotalEl = row.querySelector('[data-cart-subtotal]');
                let quantity = parseInt(quantityInput.value, 10);
                const max = parseInt(quantityInput.max, 10);

                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                }
                if (!isNaN(max) && quantity > max) {
                    quantity = max;
                    quantityInput.value = max;
                }

                const subtotal = price * quantity;
                subtotalEl.textContent = formatMoney(subtotal);
                grandTotal += subtotal;
            });

            totalEl.textContent = formatMoney(grandTotal);
        }

        rows.forEach(row => {
            const quantityInput = row.querySelector('[data-cart-quantity]');
            const form = row.querySelector('.cart-update-form');

            quantityInput.addEventListener('input', function() {
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
        });
    });
</script>
@endpush
