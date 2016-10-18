@extends('customer.front')

@include('customer.product.meta')

@section('schema-type')
    https://schema.org/Product
@endsection

@section('body')

    <h1 class="product-title" itemprop="name">{{ $product->name() }}</h1>

    <div class="row">

        <div class="col-md-8">

            @include('customer.product.images')

        </div>

        <div class="col-md-4">

            @include('customer.product.add-to-basket')

            <p class="product-price price">{{$product->priceF()}}</p>

            <p>{{ $product->description() }}</p>

            <div class="alert alert-info" role="alert">
                <span class="icon icon-truck"></span>&nbsp;
                Delivery is <strong>free</strong> for all UK orders. The usual
                delivery time is <strong>2-3 days</strong>.
            </div>

            <table class="table product-details">
                <tr>
                    <td>Reference</td>
                    <td>{{ $product->sku() }}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{ $product->name() }}</td>
                </tr>
                @if ($product->tags->count())
                    <tr>
                        <td>Tags</td>
                        <td>
                            @foreach ($product->tags as $tag)
                                <a href="{{ route(
                                    'tag::view', [$tag->id, $tag->name]
                                ) }}" class="product-tag">
                                {{ $tag->name  }}<!--
                                --></a>&nbsp;
                            @endforeach
                        </td>
                    </tr>
                @endif
            </table>

        </div>

    </div>

    <hr>

    @if ($similar && count($similar))
        <section class="product-section">

            <h2>Similar products</h2>

            @foreach ($similar as $similarProduct)
                @include(
                    'customer.product.section',
                    ['product' => $similarProduct, 'level' => 3]
                )
            @endforeach

        </section>
    @endif

    <section class="product-section">

        <h2>Product link</h2>

        <a href="{{ $location->viewHrefFor($product) }}"
           title="{{ $product->name() }}">
            {{ $location->viewHrefFor($product) }}
        </a>

    </section>

@endsection
