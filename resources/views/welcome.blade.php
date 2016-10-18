@extends('customer.front')

@section('page-title', 'Ching Shop')

@section('body')

    <h1 class="home-title">Beautiful 3D Pop-Up Cards</h1>

    <section>
        @foreach($productRows as $products)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-3">
                        <h3>
                            <a href="{{ $location->viewHrefFor($product) }}">
                                {{ $product->name() }}
                            </a>
                        </h3>
                        @if (count($product->images()))
                            <p class="price">
                                {{$product->priceF()}} |
                                @if ($product->isInStock())
                                    In stock
                                @else
                                    Out of stock
                                @endif
                            </p>
                            <a href="{{ $location->viewHrefFor($product) }}">
                                <img src="{{ $product->mainImage()->sizeUrl() }}"
                                     @if ($product->mainImage()->isSelfHosted())
                                     srcset="{{ $product->mainImage()->srcSet() }}"
                                     @endif
                                     class="img-responsive img-rounded photo">
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>

@endsection
