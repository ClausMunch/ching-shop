@extends('layouts.staff.dashboard')

@section('page-title')
    {{ $product->sku() }} / {{ $product->name() }}
@endsection

@section('header')
    <small>Product</small>
    {{ $product->name() }}
    <small>({{ $product->sku() }})</small>
@endsection

@section('heading')
    <div class="pull-right">
        <a class="btn btn-default"
           href="{{ route('catalogue.staff.products.edit', $product->sku()) }}">
            Edit product
        </a>
    </div>
@endsection

@section('content')

    <section id="description">
        <p>
            &ldquo;{{ $product->description() }}&rdquo;
        </p>
    </section>

    <hr>

    <section id="price">
        <div class="row">
            <div class="col-md-6">
                <div class="well">
                    <h3>Price</h3>
                    @include('catalogue::staff.products.price')
                </div>
            </div>
            <div class="col-md-6">
                <div class="well">
                    <h3>Tags</h3>
                    @include('catalogue::staff.products.tags')
                </div>
            </div>
        </div>
    </section>

    <hr>

    <section id="images">
        <h3>General images</h3>

        @include('catalogue::staff.products.images', ['parent' => $product])

        <hr>

        <div class="row">
            <div class="col-md-8">
                <div class="well">
                <form method="post"
                      enctype="multipart/form-data"
                      id="new-images-form"
                      class="form-inline"
                      action="{{ route(
                  'catalogue.staff.products.post-images',
                  ['sku' => $product->sku()]
              ) }}">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="form-group">
                        <label for="new-image[]">
                            Add
                            @if ($product->isStored()) new @endif
                            general images
                        </label>
                        <input type="file" name="new-image[]" id="new-image" multiple>
                        @foreach($reply->errorsFor('new-image.0') as $error)
                            <label class="help-block" for="new-image[]">
                                {{ $error }}
                            </label>
                        @endforeach
                    </div>
                    <button type="submit"
                            class="btn btn-success"
                            form="new-images-form"
                            value="submit-new-images"
                            name="submit-new-images"
                            id="submit-new-images">
                        <span class="glyphicon glyphicon-plus"></span>
                        Add general images
                    </button>
                </form>
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>

    </section>

    <hr>

    <section id="options">
        <h3>Product options</h3>

        @include('catalogue::staff.products.options')

    </section>

@endsection

@section('footer')

    <a class="btn btn-link" href="{{ $location->viewHrefFor($product) }}">
        View on site
    </a>

    <a class="btn btn-default"
       href="{{ route('catalogue.staff.products.edit', $product->sku()) }}">
        Edit product
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
