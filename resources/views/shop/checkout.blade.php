@extends('layouts.app')

@section('hero-title', $article->title)

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <a href="{{ url('/') }}" class="btn btn-sm mb-5">&larr; {{ __('site.back_to_shop') }}</a>

        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <div class="p-3 p-lg-4 border bg-white">
                    <div class="product-thumbnail slider-container" style="height: 400px;">
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

                    <form action="{{ route('shop.order.submit', $article->id) }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="quantity" class="text-black">{{ __('site.quantity') }} <span class="text-danger">*</span></label>
                                <input type="number" id="quantity" name="quantity" min="1" max="{{ $article->quantity }}" value="1" required class="form-control" {{ $article->quantity <= 0 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_first_name" class="text-black">{{ __('site.first_name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="customer_first_name" name="customer_first_name" required class="form-control">
                            </div>
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

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="customer_last_name" class="text-black">{{ __('site.last_name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="customer_last_name" name="customer_last_name" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="text-black">{{ __('site.city') }} <span class="text-danger">*</span></label>
                                <input type="text" id="city" name="city" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="email" class="text-black">{{ __('site.email') }} <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="text-black">{{ __('site.phone_number') }} <span class="text-danger">*</span></label>
                                <input type="text" id="phone_number" name="phone_number" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="text-black">{{ __('site.address') }} <span class="text-danger">*</span></label>
                            <textarea id="address" name="address" required class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg py-3 w-100 mt-3" {{ $article->quantity <= 0 ? 'disabled' : '' }}>
                            {{ $article->quantity <= 0 ? __('site.out_of_stock') : __('site.submit_order') }}
                        </button>
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

        quantityInput.addEventListener('input', recalcBreakdown);
    });
</script>
@endpush
@endsection
