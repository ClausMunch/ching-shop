@extends('layouts.staff.dashboard')

@section('page-title')
    {{ $product->sku() }} / {{ $product->name() }}
@endsection

@section('header')
    <small>Product</small>
    {{ $product->name() }}
    <small>({{ $product->sku() }})</small>
@endsection

@section('content')

    <section>
        <p>
            &ldquo;{{ $product->description() }}&rdquo;
        </p>
    </section>

    <section>
        <h3>Price</h3>
        @include('staff.products.price')
    </section>

    <section>
        <h3>Images</h3>

        @include('staff.products.images')

        <hr>

        <div class="row">
            <div class="col-md-6">
                <form method="post"
                      enctype="multipart/form-data"
                      id="new-images-form"
                      class="form-inline"
                      action="{{ route(
                  'staff.products.post-images',
                  ['sku' => $product->sku()]
              ) }}">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="form-group">
                        <label for="new-image[]">
                            Add @if ($product->isStored()) new @endif images
                        </label>
                        <input type="file" name="new-image[]" id="new-image" multiple>
                        @foreach($reply->errorsFor('new-image.0') as $error)
                            <label class="help-block" for="new-image[]">
                                {{ $error }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-plus"></span>
                        Add images
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="post"
                      id="product-tags-form"
                      class="form-inline"
                      action="{{ route(
                  'staff.products.put-tags',
                  ['sku' => $product->sku()]
              ) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <label for="tag-ids">
                        Tags
                    </label>
                    <select class="form-control"
                            name="tag-ids"
                            id="tag-ids"
                            multiple="multiple">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                    id="tag-option-{{ $tag->id  }}"
                                @if ($product->tags->contains('id', $tag->id))
                                    selected
                                @endif
                            >
                                {{ $tag->name }}
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
            </div>
        </div>

    </section>

@endsection

@section('footer')

    <a class="btn btn-link" href="{{ $location->viewHrefFor($product) }}">
        View on site
    </a>

    <a class="btn btn-default"
       href="{{ route('staff.products.edit', $product->sku()) }}">
        Edit
    </a>

    <div class="pull-right">
        <form method="post"
              id="delete-product-form"
              action="{{ $location->deleteActionFor($product) }}">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <button type="submit" class="btn btn-danger">
                Delete
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        window.onload = function () {
            $("#tag-ids").multiselect({
                enableFiltering: true,
                checkboxName: "tag-ids[]"
            });
        };
    </script>
@endpush
