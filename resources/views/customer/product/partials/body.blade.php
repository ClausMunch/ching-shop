@include('customer.category.breadcrumb', ['leaf' => $product->category])

@include('flash::message')

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
            delivery time is <strong>2-3 working days</strong>.
        </div>

        <table class="table product-details">
            <tr>
                <td>Reference</td>
                <td id="sku">{{ $product->sku() }}</td>
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

<section class="row product-section">

    <div class="col-md-6">
        <h2>Share</h2>

        <p>
            <a href="{{$product->emailShareUrl()}}" class="icon-link"
               title="Share by Email" target="_blank">
                <span class="icon icon-mail4"></span>
                <span class="sr-only">Share by e-mail</span>
            </a>&nbsp;
            <a href="{{$product->pinterestShareUrl()}}"
               target="_blank"
               class="pin-it-button icon-link"
               title="Pin on Pinterest">
                <span class="icon icon-pinterest-with-circle"></span>
                <span class="sr-only">Pin on Pinterest</span>
            </a>&nbsp;
            <a target="_blank" title="Share on Facebook" class="icon-link"
               href="{{$product->facebookShareUrl()}}">
                <span class="icon icon-facebook-with-circle"></span>
                <span class="sr-only">Share on Facebook</span>
            </a>&nbsp;
            <a target="_blank" title="Share on Twitter" class="icon-link"
               href="{{$product->twitterShareUrl()}}">
                <span class="icon icon-twitter-with-circle"></span>
                <span class="sr-only">Share on Twitter</span>
            </a>
        </p>
    </div>

    <div class="col-md-6">
        <h2>Product link</h2>

        <a href="{{ $location->viewHrefFor($product) }}"
           title="{{ $product->name() }}">
            {{ $location->viewHrefFor($product) }}
        </a>
    </div>

</section>

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
