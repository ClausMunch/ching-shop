<table class="table table-striped">
    <thead>
    @if ($categories->first())
        <tr>
            <th>
                Category name
            </th>
            <th>
                Parent
            </th>
            <th>
                Children
            </th>
            <th>
                Products
            </th>
            <th>
                Actions
            </th>
        </tr>
    @endif
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td>
                {{$category->name}}
            </td>
            <td>
                @include('catalogue::staff.categories.parent')
            </td>
            <td>
                @include(
                    'catalogue::staff.categories.display',
                    [
                        'categories' => $category->children,
                    ]
                )
            </td>
            <td>
                <small>
                    @foreach ($category->products as $product)
                        <a href="{{route('products.show', [$product->sku])}}">
                            {{$product->sku}}</a>&nbsp;
                    @endforeach
                </small>
            </td>
            <td>
                <form method="post"
                      id="delete-image-form"
                      class="form-confirm"
                      action="{{$location->deleteActionFor($category)}}">
                    {{method_field('DELETE')}}
                    {{csrf_field()}}
                    <button type="submit"
                            class="btn btn-sm btn-danger"
                            id="delete-tag-{{$category->id}}">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
