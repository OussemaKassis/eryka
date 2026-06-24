@forelse($articles as $article)
    @include('shop.partials.product-card', ['article' => $article])
@empty
    <div class="col-12 text-center">
        <p>{{ __('site.no_products_found_category') }}</p>
    </div>
@endforelse
