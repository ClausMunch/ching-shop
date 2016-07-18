<li class="product-image-sortable ui-state-default"
    data-image-id="{{ $image->id }}">
    <form class="staff-product-form image-form"
          id="detach-image-{{ $image->id }}-form"
          method="post"
          draggable="true"
          action="{{ route(
            $parent->routePath() . '.detach-image',
            [$parent->id, $image->id]
          ) }}">

        <img src="{{ $image->sizeUrl('small') }}"
             @if ($image->isSelfHosted())
             srcset="{{ $image->srcSet() }}"
             @endif
             alt="{{ $image->alt_text }}"
             class="img-responsive img-rounded staff-product-image" />

        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <button type="submit"
                form="detach-image-{{ $image->id }}-form"
                class="btn btn-link delete-image">
            <span class="glyphicon glyphicon-remove"
                  aria-hidden="true">
            </span><span class="sr-only">Remove {{ $image->id }}</span>
        </button>

    </form>
</li>
