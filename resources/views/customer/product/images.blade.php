<div class="row">
    <div class="col-sm-10 col-sm-push-2">
        @if ($product->mainImage())
            <img class="img-responsive photo"
                 id="product-main-image"
                 src="{{ $product->mainImage()->sizeUrl('large') }}"
                 @if ($product->mainImage()->isSelfHosted())
                 srcset="{{ $product->mainImage()->srcSet() }}"
                 @endif
                 alt="{{ $product->mainImage()->altText() }}">
        @endif
    </div>
    <div class="col-sm-2 col-sm-pull-10">
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
