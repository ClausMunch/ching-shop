<div class="table-responsive"
     id="product-options"
     data-product-id="{{ $product->id }}">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>
                Label
            </th>
            <th>
                Supplier number
            </th>
            <th>
                Images
            </th>
            <th>
                Stock
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($product->options as $option)
            <tr>
                <td>
                    <p contenteditable="true"
                       data-option-id="{{ $option->id }}"
                       data-original="{{ $option->label }}"
                       class="product-option-label js-only hidden">
                        {{ $option->label }}
                    </p>
                </td>
                <td>
                    <form method="post"
                          id="option-{{$option->id}}-supplier-number-form"
                          class="form-inline"
                          action="{{ route(
                            'catalogue.staff.products.options.put-supplier-number',
                            [$option->id]
                          ) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="id"
                                   value="{{$option->id}}">
                            <label for="option-{{$option->id}}-supplier-number"
                                   class="sr-only">
                                Supplier number
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="text"
                                       name="supplier-number"
                                       id="option-{{$option->id}}-supplier-number"
                                       class="form-control"
                                       value="{{$reply->oldInputOr(
                                           'supplier-number',
                                           $option->supplier_number ?? ''
                                       )}}"
                                       minlength="2"
                                       maxlength="63"/>
                                <span class="input-group-btn">
                                    <button type="submit"
                                            class="btn btn-success">
                                        <span class="glyphicon glyphicon-check">
                                        </span>
                                        <span class="sr-only">
                                            Set {{$option->label}} supplier number
                                        </span>
                                    </button>
                                </span>
                            </div>
                            @foreach($reply->errorsFor('supplier-number') as $error)
                                <label class="help-block"
                                       for="option-{{$option->id}}-supplier-number">
                                    {{$error}}
                                </label>
                            @endforeach
                        </div>
                    </form>
                </td>
                <td>
                    @include(
                        'catalogue::staff.products.images',
                        ['parent' => $option]
                    )
                </td>
                <td>
                    <form class="form" method="post"
                          action="{{
                              route(
                                'catalogue.staff.products.options.stock',
                                [$option->id]
                              )
                        }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="input-group input-group-sm">
                            <label for="quantity"
                                   class="sr-only">Quantity</label>
                            <input name="quantity" type="number" size="3"
                                   id="option-{{$option->id}}-stock"
                                   min="0" max="999" step="1"
                                   title="Quantity of option {{$option->label}}"
                                   class="form-control"
                                   value="{{count($option->availableStock)}}"
                                   required>
                            <span class="input-group-btn">
                        <button class="btn btn-success"
                                id="search-button"
                                type="submit">
                            <span class="glyphicon glyphicon-ok"></span>
                            <span class="sr-only">Save stock</span>
                        </button>
                    </span>
                        </div>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<hr>

<div class="well">
    <form method="post"
          id="new-option-form"
          class="form-inline"
          action="{{ route(
                 'catalogue.staff.products.post-option',
                  ['id' => $product->id]
              ) }}">
        {{ csrf_field() }}
        {{ method_field('POST') }}
        <div class="form-group">
            <label for="new-option">
                Add new option
            </label>
            <input type="text"
                   class="form-control"
                   name="label"
                   minlength="2"
                   maxlength="127"
                   required="required"
                   placeholder="Label..."
                   id="new-option"/>
            <input type="hidden"
                   required="required"
                   id="option-product"
                   name="option-product"
                   value="{{ $product->id }}"/>
        </div>
        <button type="submit"
                form="new-option-form"
                id="new-option-button"
                class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span>
            Add option
        </button>
        @foreach($reply->errorsFor('label') as $error)
            <label class="help-block" for="label">
                {{ $error }}
            </label>
        @endforeach
    </form>
</div>

@push('scripts')
<script type="text/javascript"
        src="{{ elixir('js/product-options.js') }}">
</script>
@endpush
