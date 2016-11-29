<form method="post"
      id="product-tags-form"
      class="form-inline"
      action="{{route(
                  'catalogue.staff.products.put-tags',
                  ['sku' => $product->sku()]
              )}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <label for="tag-ids">
        Select tags
    </label>
    <select class="form-control"
            name="tag-ids"
            id="tag-ids"
            multiple="multiple">
        @foreach ($tags as $tag)
            <option value="{{$tag->id}}"
                    id="tag-option-{{$tag->id}}"
                    @if ($product->tags->contains('id', $tag->id))
                    selected
                    @endif
            >
                {{$tag->name}}
            </option>
        @endforeach
    </select>
    <button type="submit"
            id="save-tags-button"
            class="btn btn-success">
        <span class="glyphicon glyphicon-check"></span>
        Save tags
    </button>
</form>
