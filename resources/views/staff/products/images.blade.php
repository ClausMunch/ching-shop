<ul class="product-images-sortable"
    data-product-id="{{ $product->id() }}"
    data-token="{{ csrf_token() }}">
    @foreach($product->images() as $image)

    <li class="product-image-sortable ui-state-default"
        data-image-id="{{ $image->id }}">
        <form class="form-inline form-full-inline staff-product-form image-form"
              id="detach-image-{{ $image->id }}-form"
              method="post"
              draggable="true"
              action="{{ $location->detachActionFor($product, $image) }}">

            <img src="{{ $image->url('small') }}"
                 srcset="{{ $image->srcSet() }}"
                 alt="{{ $image->alt_text }}"
                 class="img-responsive img-rounded staff-product-image" />

            {{ method_field('DELETE') }}
            {{ csrf_field() }}

            <button type="submit"
                    form="detach-image-{{ $image->id }}-form"
                    class="btn btn-link delete-image">
                            <span class="glyphicon glyphicon-remove"
                                  aria-hidden="true">
                            </span><span class="sr-only">Remove</span>
            </button>

        </form>
    </li>

    @endforeach
</ul>
