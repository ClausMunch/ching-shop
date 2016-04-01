@extends('customer.front')

@section('page-title')
    {{ $product->name() }} ({{ $product->sku() }})
@endsection

@section('body')

    <h1 class="product-title">{{ $product->name() }}</h1>

    <div class="row">

        <div class="col-md-8">

            @if ($product->mainImage())
            <img class="img-responsive photo"
                 id="product-main-image"
                 src="{{ $product->mainImage()->url('large') }}"
                 srcset="{{ $product->mainImage()->srcSet() }}"
                 alt="{{ $product->mainImage()->altText() }}">
            @endif

            <div class="product-thumbnails">
                @foreach($product->images() as $i => $image)
                    <a class="product-thumbnail"
                       @if ($i === 0) data-selected="true" @endif
                        href="{{ $image->url() }}"
                        title="{{ $image->altText() }}">
                        <img class="img-thumbnail img-responsive"
                             src="{{ $image->url('large') }}"
                             srcset="{{ $image->srcSet() }}"
                             alt="{{ $image->altText() }}"
                             width="128" height="97">
                    </a>
                @endforeach
            </div>

        </div>

        <div class="col-md-4">

            <a class="btn btn-success btn-lg btn-block buy-button"
               href="{{ $location->productEnquiryMail($product) }}">
                Buy this: {{ $product->price() }}
            </a>

            <p>{{ $product->description() }}</p>

            <table class="table product-details">
                <tr>
                    <td>Reference</td>
                    <td>{{ $product->sku() }}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>{{ $product->name() }}</td>
                </tr>
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
