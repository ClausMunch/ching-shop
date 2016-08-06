<form method="post"
      action="{{ route('sales.customer.add-to-basket') }}">
    {{ csrf_field()  }}
    @if ($product->options->count() > 1)
        <div class="form-group-lg product-options-group">
            <label for="product-option-choice">
                Option:
            </label>
            <select class="form-control input-lg"
                    id="product-option-choice"
                    name="product-option">
                @foreach ($product->options as $option)
                    <option value="{{ $option->id }}"
                            id="product-option-choice-{{ $option->id }}">
                        {{ $option->label  }}
                    </option>
                @endforeach
            </select>
        </div>
    @elseif ($product->options->count() === 1)
        <input type="hidden"
               id="product-option-only"
               class="product-option-only"
               name="product-option"
               value="{{ $product->options->first()->id }}" />
    @endif
    <button type="submit"
            class="btn btn-success btn-lg btn-block buy-button">
        Add to basket
    </button>
</form>
