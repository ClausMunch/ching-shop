<form method="post"
      enctype="multipart/form-data"
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

    <hr>
    <h4>Images</h4>
    <div id="images">
        @foreach($product->images() as $image)
            <div class="form-group"
                 {{ $reply->putHasError("image-{$image->id}") }}>
                <label for="image-filename-{{ $image->id }}">
                    {{ $image->alt_text }}
                </label>
                <input type="text" id="image-filename-{{ $image->id }}"
                       name="image-filename-{{ $image->id }}"
                       readonly>
            </div>
        @endforeach
        <label for="new-image[]">
            Upload @if ($product->isStored()) new @endif images
        </label>
        <input type="file" name="new-image[]" id="new-image[]" multiple>
    </div>
    <hr>

    <button type="submit" class="btn btn-success">Save</button>
</form>
