<form method="post"
      enctype="multipart/form-data"
      id="product-form"
      action="{{ $location->persistActionFor($product) }}">
    {{ csrf_field() }}
    {{ method_field($location->persistMethodFor($product)) }}
    <input type="hidden" name="id" value="{{ $product->ID() }}">
    <div class="form-group {{ $reply->putHasError('name') }}">
        <label for="name">
            Product name
        </label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               maxlength="255"
               required
               value="{{ $reply->oldInputOr('name', $product->name()) }}">
        @foreach($reply->errorsFor('name') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </div>
    <div class="form-group {{ $reply->putHasError('sku') }}">
        <label for="sku">
            Product SKU
        </label>
        <input type="text"
               class="form-control"
               id="sku"
               name="sku"
               maxlength="255"
               required
               value="{{ $reply->oldInputOr('sku', $product->SKU()) }}">
        @foreach($reply->errorsFor('sku') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </div>
    <div class="form-group {{ $reply->putHasError('slug') }}">
        <label for="slug">
            URL slug
        </label>
        <input type="text"
               class="form-control"
               id="slug"
               name="slug"
               maxlength="128"
               required
               value="{{ $reply->oldInputOr('slug', $product->slug()) }}">
        @foreach($reply->errorsFor('slug') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </div>

    <div id="images">
        <label for="new-image[]">
            Upload @if ($product->isStored()) new @endif images
        </label>
        <input type="file" name="new-image[]" id="new-image" multiple>
    </div>

    <hr>

    <button type="submit" class="btn btn-success">Save</button>
</form>
