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
                 src="{{ $product->mainImage()->sizeUrl('large') }}"
                 @if ($product->mainImage()->isSelfHosted())
                    srcset="{{ $product->mainImage()->srcSet() }}"
                 @endif
                 alt="{{ $product->mainImage()->altText() }}">
            @endif

            <div class="product-thumbnails">
                @foreach($product->images() as $i => $image)
                    <a class="product-thumbnail"
                       @if ($i === 0) data-selected="true" @endif
                        href="{{ $image->sizeUrl('large') }}"
                        title="{{ $image->altText() }}">
                        <img class="img-thumbnail img-responsive"
                             src="{{ $image->sizeUrl('large') }}"
                             alt="{{ $image->altText() }}"
                             @if ($image->isSelfHosted())
                                srcset="{{ $image->srcSet() }}"
                             @endif
                             width="128" height="97">
                    </a>
                @endforeach
                @foreach ($product->options as $option)
                    @foreach ($option->images as $image)
                            <a class="product-thumbnail"
                               data-option-id="{{ $option->id  }}"
                               href="{{ $image->sizeUrl('large') }}"
                               title="{{ $option->label }} ({{ $image->altText() }})">
                                <img class="img-thumbnail img-responsive"
                                     src="{{ $image->sizeUrl('large') }}"
                                     alt="{{ $image->altText() }}"
                                     @if ($image->isSelfHosted())
                                     srcset="{{ $image->srcSet() }}"
                                     @endif
                                     width="128" height="97">
                            </a>
                    @endforeach
                @endforeach
            </div>

        </div>

        <div class="col-md-4">

            @include('customer.product.add-to-basket')

            <p class="product-price price-display">{{$product->price()}}</p>

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
