@extends('customer.front')

@section('page-title', 'Ching Shop')

@section('body')

    <h1 class="home-title">
        Ching Shop
    </h1>

    <section>
        <h2>Stuff we love</h2>
        <div class="row">
            @foreach($productColumns as $products)
                <div class="col-md-6 col-lg-3">
                    @foreach($products as $product)
                        <h3>
                            <a href="{{ $location->viewHrefFor($product) }}">
                                {{ $product->name() }}
                            </a>
                        </h3>
                        @if (count($product->images()))
                            <a href="{{ $location->viewHrefFor($product) }}">
                                <img src="{{ $product->images()[0]->url() }}"
                                     srcset="{{ $product->mainImage()->srcSet() }}"
                                     class="img-responsive img-rounded photo">
                            </a>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

@endsection
