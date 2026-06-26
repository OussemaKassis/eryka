@php
    $outOfStock = $article->quantity <= 0;
    $lowStock = !$outOfStock && $article->quantity <= 5;
@endphp

<div class="col-12 col-md-4 col-lg-3 mb-5">
    <div class="product-item">
        <a href="{{ route('shop.product', $article->id) }}" class="d-block text-decoration-none">
            <div class="product-thumbnail slider-container">
                @if($outOfStock)
                    <span class="badge bg-danger position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.out_of_stock') }}</span>
                @elseif($lowStock)
                    <span class="badge bg-warning text-dark position-absolute" style="top: 10px; left: 10px; z-index: 11;">{{ __('site.only_x_left', ['qty' => $article->quantity]) }}</span>
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
            </div>

            <h3 class="product-title">{{ $article->title }}</h3>
        </a>
        <strong class="product-price">{{ number_format($article->price, 2) }} DT</strong>
        <div class="mt-3 d-flex flex-column align-items-center gap-2 product-actions">
            @if($outOfStock)
                <span class="btn btn-sm disabled">{{ __('site.out_of_stock') }}</span>
            @else
                <a href="{{ route('shop.checkout', $article->id) }}" class="btn btn-primary btn-sm">{{ __('site.order_now') }}</a>
            @endif
            <button type="button" class="btn btn-outline-brand btn-sm" data-bs-toggle="modal" data-bs-target="#quickview-{{ $article->id }}">{{ __('site.quick_view') }}</button>
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickview-{{ $article->id }}" tabindex="-1" aria-labelledby="quickview-{{ $article->id }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickview-{{ $article->id }}-label">{{ $article->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('site.close') }}"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-thumbnail{{ $article->images->first() ? '' : ' slide-placeholder' }}" style="height: 250px; border-radius: 10px; overflow: hidden;">
                            @if($article->images->first())
                                <img src="{{ asset('storage/' . $article->images->first()->image_path) }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <i class="fa-solid fa-couch"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong class="product-price d-block mb-3" style="font-size: 1.5rem;">{{ number_format($article->price, 2) }} DT</strong>
                        @if($outOfStock)
                            <span class="badge bg-danger mb-3">{{ __('site.out_of_stock') }}</span>
                        @elseif($lowStock)
                            <span class="badge bg-warning text-dark mb-3">{{ __('site.only_x_left', ['qty' => $article->quantity]) }}</span>
                        @endif
                        @if($article->description)
                            <p>{{ $article->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('shop.product', $article->id) }}" class="btn btn-outline-brand btn-sm">{{ __('site.view_full_details') }}</a>
                @if(!$outOfStock)
                    <a href="{{ route('shop.checkout', $article->id) }}" class="btn btn-primary btn-sm">{{ __('site.order_now') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
