<form method="post"
      id="product-offers-form"
      class="form-inline"
      action="{{route(
                  'product.put-offers',
                  ['sku' => $product->sku()]
              )}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <label for="offer-ids">
        Select offers
    </label>
    <select class="form-control multi"
            name="offer-ids[]"
            id="offer-ids"
            multiple="multiple">
        @foreach ($offers as $offer)
            <option value="{{$offer->id}}"
                    id="offer-option-{{$offer->id}}"
                    @if ($product->offers->contains('id', $offer->id))
                    selected
                    @endif
            >
                {{$offer->name}}
            </option>
        @endforeach
    </select>
    <button type="submit"
            id="save-offers-button"
            class="btn btn-success">
        <span class="glyphicon glyphicon-check"></span>
        Save offers
    </button>
</form>
