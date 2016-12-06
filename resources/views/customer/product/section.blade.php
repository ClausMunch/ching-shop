<section class="product-section">
    <div class="row">
        <div class="col-md-4">
            <img class="img-responsive photo"
                 alt="{{ $product->mainImage()->altText() }}"
                 @if ($product->mainImage()->isSelfHosted())
                 srcset="{{ $product->mainImage()->srcSet() }}"
                 @endif
                 src="{{ $product->mainImage()->sizeUrl('medium') }}">
        </div>
        <div class="col-md-8">
            <h{{$level or 2}}>
                <a href="{{ $location->viewHrefFor($product) }}">
                    {{ $product->name() }}
                </a>
            </h{{$level or 2}}>
            <p>{{$product->description()}}</p>
            <hr>
            <div class="row">
                <div class="col-md-2">
                    <p class="price product-price">
                        {{$product->priceF()}}
                    </p>
                </div>
                <div class="col-md-10">
                    @include('customer.product.add-to-basket')
                </div>
            </div>
        </div>
    </div>
</section>
