@extends('customer.front')

@section('page-title', 'Ching Shop')

@section('body')

    <h1 class="home-title">Beautiful 3D Pop-Up Cards</h1>

    <section>
        @foreach($productRows as $products)
            <div class="row front-product-row">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-3">
                        @if (count($product->images()))
                            <a href="{{ $location->viewHrefFor($product) }}">
                                <img src="{{ $product->mainImage()->sizeUrl() }}"
                                     @if ($product->mainImage()->isSelfHosted())
                                     srcset="{{ $product->mainImage()->srcSet() }}"
                                     @endif
                                     class="img-responsive img-rounded photo">
                            </a>
                        @endif
                        <h3 class="front-product-name">
                            <a href="{{ $location->viewHrefFor($product) }}">
                                {{ $product->name() }}
                            </a>
                        </h3>
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>

@endsection
