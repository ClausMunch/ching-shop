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
        @foreach($product->images() as $image)

            <form class="form-inline form-full-inline staff-product-form"
                  id="detach-image-{{ $image->id }}-form"
                  method="post"
                  action="{{ $location->detachActionFor($product, $image) }}">

                <img src="{{ $image->url('small') }}"
                     srcset="{{ $image->srcSet() }}"
                     alt="{{ $image->alt_text }}"
                     class="img-responsive img-rounded staff-product-image" />

                {{ method_field('DELETE') }}
                {{ csrf_field() }}

                <button type="submit"
                        form="detach-image-{{ $image->id }}-form"
                        class="btn btn-link">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true">
                    </span><span class="sr-only">Remove</span>
                </button>

            </form>

        @endforeach
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
