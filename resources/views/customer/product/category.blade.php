@extends('customer.front')

@section('page-title')
    Cards
@endsection

@section('body')

    <h1 class="product-title">Cards</h1>

    @foreach ($products as $product)
        <section class="product-section">
            <div class="row">
                <div class="col-md-4">
                    <img class="img-responsive photo"
                         alt="{{ $product->mainImage()->altText() }}"
                         src="{{ $product->mainImage()->url() }}">
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
    @endforeach

@endsection
