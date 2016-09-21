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
               minlength="10"
               maxlength="255"
               required
               value="{{ $reply->oldInputOr('name', $product->name()) }}">
        @foreach($reply->errorsFor('name') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </div>
    <div class="form-group {{ $reply->putHasError('description') }}">
        <label for="description">
            Product description
        </label>
        <textarea class="form-control"
               id="description"
               name="description"
               minlength="16"
               maxlength="512"
               required>{{ $reply->oldInputOr('description', $product->description()) }}</textarea>
        @foreach($reply->errorsFor('description') as $error)
            <label class="help-block" for="description">
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
               value="{{ $reply->oldInputOr('sku', $product->sku()) }}">
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
               minlength="5"
               maxlength="128"
               required
               value="{{ $reply->oldInputOr('slug', $product->slug()) }}">
        @foreach($reply->errorsFor('slug') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </div>

    <hr>

    <button type="submit" class="btn btn-success">Save</button>
</form>
