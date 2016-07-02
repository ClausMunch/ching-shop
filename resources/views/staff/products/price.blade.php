<form class="form-inline"
      action="{{ route('staff.products.price', [
                'sku' => $product->sku()
              ]) }}"
      method="post"
      id="set-price-form">
    {{ csrf_field() }}
    <div class="form-group
                {{ $reply->putHasError('units') }}
                {{ $reply->putHasError('subunits') }}">
        <label for="units">£</label>
        <input type="number"
               class="form-control price-input"
               name="units"
               width="3"
               size="3"
               maxlength="3"
               min="0"
               max="999"
               id="units"
               value="{{ $reply->oldInputOr('units', $product->priceUnits()) }}"
               required>
        .
        <input type="number"
               class="form-control price-input"
               name="subunits"
               width="2"
               size="2"
               maxlength="2"
               min="0"
               max="99"
               id="subunits"
               value="{{ $reply->oldInputOr('subunits', $product->priceSubUnits()) }}"
               required>
        <label for="subunits">p</label>

        <button type="submit" class="btn btn-default" form="set-price-form">
            Set price
        </button>

        @foreach($reply->errorsFor('units') as $error)
            <label class="help-block" for="units">
                {{ $error }}
            </label>
        @endforeach
        @foreach($reply->errorsFor('subunits') as $error)
            <label class="help-block" for="units">
                {{ $error }}
            </label>
        @endforeach

    </div>
</form>
