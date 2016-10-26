<form method="post"
      id="product-category-form"
      class="form-inline"
      action="{{route(
                  'catalogue.staff.products.put-category',
                  ['sku' => $product->sku()]
              )}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <label for="category-id">
        Select category
    </label>
    <select class="form-control"
            name="category-id"
            id="category-id">
        @foreach ($categories as $category)
            <option value="{{$category->id}}"
                    id="category-option-{{$category->id}}"
                    @if ($product->category && $product->category->id === $category->id)
                    selected
                    @endif
            >
                {{$category->name}}
            </option>
        @endforeach
    </select>
    <button type="submit"
            id="save-category-button"
            class="btn btn-success">
        <span class="glyphicon glyphicon-check"></span>
        Set category
    </button>
</form>
