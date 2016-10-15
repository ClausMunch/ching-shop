@extends('customer.front')

@section('page-title')
    {{ $product->name() }} ({{ $product->sku() }})
@endsection

@section('body')

    <h1 class="product-title">{{ $product->name() }}</h1>

    <div class="row">

        <div class="col-md-8">

            @include('customer.product.images')

        </div>

        <div class="col-md-4">

            @include('customer.product.add-to-basket')

            <p class="product-price price">{{$product->price()}}</p>

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
                                ) }}">
                                {{ $tag->name  }}<!--
                                --></a>&nbsp;
                            @endforeach
                        </td>
                    </tr>
                @endif
            </table>

        </div>

    </div>

    <section class="product-section">

        <h3>Product link</h3>

        <a href="{{ $location->viewHrefFor($product) }}"
           title="{{ $product->name() }}">
            {{ $location->viewHrefFor($product) }}
        </a>

    </section>

@endsection
