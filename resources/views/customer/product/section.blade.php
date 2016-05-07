<section class="product-section">
    <div class="row">
        <div class="col-md-4">
            <img class="img-responsive photo"
                 alt="{{ $product->mainImage()->altText() }}"
                 @if ($product->mainImage()->isSelfHosted())
                 srcset="{{ $product->mainImage()->srcSet() }}"
                 @endif
                 src="{{ $product->mainImage()->url('medium') }}">
        </div>
        <div class="col-md-8">
            <h2>
                <a href="{{ $location->viewHrefFor($product) }}">
                    {{ $product->name() }}
                </a>
            </h2>
            {{ $product->description() }}
        </div>
    </div>
</section>
