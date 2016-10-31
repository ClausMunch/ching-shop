<form method="post"
      id="product-form" data-toggle="validator" role="form"
      action="{{ $location->persistActionFor($product) }}">
    {{ csrf_field() }}
    {{ method_field($location->persistMethodFor($product)) }}
    <input type="hidden" name="id" value="{{ $product->ID() }}">
    <div class="form-group {{ $reply->putHasError('name') }}">
        <label for="name">
            Product name
        </label>
        <input type="text"
               class="form-control counted"
               id="name"
               name="name"
               minlength="10"
               maxlength="255"
               data-ideal-length="50"
               required
               value="{{ $reply->oldInputOr('name', $product->name()) }}">
        @foreach($reply->errorsFor('name') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
        <label class="help-block pull-right" for="name">
            Ideal length 50 |
            <a href="https://moz.com/learn/seo/title-tag"
               target="_blank"
               title="Learn more about page titles."
               class="help-link">
                https://moz.com/learn/seo/title-tag
            </a>
        </label>
        <label class="pull-left counter" for="name">
            {{mb_strlen($reply->oldInputOr('name', $product->name()))}}
        </label>
    </div>
    <hr>
    <div class="form-group {{ $reply->putHasError('description') }}">
        <label for="description">
            Product description
        </label>
        <textarea class="form-control counted"
                  id="description"
                  name="description"
                  minlength="16"
                  maxlength="512"
                  data-ideal-length="155"
                  required>{{ $reply->oldInputOr('description', $product->description()) }}</textarea>
        @foreach($reply->errorsFor('description') as $error)
            <label class="help-block" for="description">
                {{ $error }}
            </label>
        @endforeach
        <label class="help-block pull-right" for="name">
            Ideal length 155 |
            <a href="https://moz.com/learn/seo/meta-description"
               target="_blank"
               title="Learn more about page descriptions."
               class="help-link">
                https://moz.com/learn/seo/meta-description
            </a>
        </label>
        <label class="pull-left counter" for="description">
            {{mb_strlen($reply->oldInputOr('description', $product->description))}}
        </label>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
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
        </div>
        <div class="col-md-6">
            <div class="form-group {{$reply->putHasError('supplier_number')}}">
                <label for="supplier_number">
                    Supplier number
                </label>
                <input type="text"
                       class="form-control supplier_number"
                       id="supplier_number"
                       name="supplier_number"
                       minlength="1"
                       maxlength="63"
                       value="{{$reply->oldInputOr('supplier_number', $product->supplier_number ?? '')}}">
                @foreach($reply->errorsFor('supplier_number') as $error)
                    <label class="help-block" for="supplier_number">
                        {{ $error }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="form-group {{ $reply->putHasError('slug') }}">
        <label for="slug">
            URL slug
        </label>
        <div class="input-group">
            <input type="text"
                   class="form-control slug"
                   id="slug"
                   name="slug"
                   minlength="5"
                   maxlength="128"
                   required
                   value="{{$reply->oldInputOr('slug', $product->slug())}}">
            <span class="input-group-btn">
                        <button class="btn btn-default" id="use-name"
                                type="button">
                           Use name
                        </button>
                    </span>
        </div>
        @foreach($reply->errorsFor('slug') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </div>

    <hr>

    <button type="submit" class="btn btn-success">Save</button>
</form>
