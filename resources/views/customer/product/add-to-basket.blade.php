<form method="post"
      class="add-to-basket-form"
      action="{{ route('sales.customer.add-to-basket') }}">
    {{ csrf_field() }}

    @unless($product->isInStock())
        <div class="alert alert-danger">
            <span class="icon icon-package"></span>&nbsp;
            Sorry, this product is currently <strong>out of stock</strong>.
            Please check back soon.
        </div>
    @endunless

    @if ($product->options->count() > 1)
        <div class="form-group-lg product-options-group">
            <label for="product-option-choice" class="sr-only">
                Option:
            </label>
            <select class="form-control input-lg"
                    id="product-option-choice"
                    name="product-option">
                @foreach ($product->options as $option)
                    @if ($option->isInStock())
                        <option value="{{$option->id}}"
                                id="product-option-choice-{{ $option->id }}">
                            {{$option->label}}
                        </option>
                    @else
                        <option value="{{$option->id}}"
                                value=""
                                disabled
                                id="product-option-choice-{{ $option->id }}">
                            {{$option->label}} (out of stock)
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    @elseif ($product->options->count() === 1)
        <input type="hidden"
               id="product-option-only"
               class="product-option-only"
               name="product-option"
               value="{{ $product->options->first()->id }}"/>
    @endif
    <button type="submit"
            id="add-{{$product->id}}-to-basket"
            @unless($product->isInStock())
            disabled
            title="Out of stock"
            @endunless
            class="btn btn-success btn-lg btn-block buy-button btn-flow">
        Add to basket <span class="glyphicon glyphicon-chevron-right"></span>
    </button>
</form>
