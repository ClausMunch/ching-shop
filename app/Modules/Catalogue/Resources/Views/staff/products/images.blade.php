<ul class="product-images-sortable connect-sortable"
    data-sort-action="{{ route(
        $parent->routePath() . '.image-order',
        $parent->crudId()
    ) }}"
    id="owner-{{ $parent->crudId() }}-images"
    data-token="{{ csrf_token() }}">
    @foreach($parent->images as $image)
        @include('catalogue::staff.products.image')
    @endforeach
</ul>
