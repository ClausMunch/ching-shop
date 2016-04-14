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
                <span class="glyphicon glyphicon-plus"></span> Add
            </button>
        </form>

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
