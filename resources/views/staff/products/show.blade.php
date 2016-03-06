@extends('layouts.staff.dashboard')

@section('page-title')
    {{ $product->SKU() }} / {{ $product->name() }}
@endsection

@section('header')
    Product {{ $product->name() }} <small>({{ $product->SKU() }})</small>
@endsection

@section('content')

    <section>
        <h3>Images</h3>
        @foreach($product->images() as $image)

            <form class="form-inline form-full-inline staff-product-form"
                  id="detach-image-{{ $image->id }}-form"
                  method="post"
                  action="{{ $location->detachActionFor($product, $image) }}">

                <img src="{{ $image->url() }}"
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

    <a class="btn btn-default"
       href="{{ route('staff.products.edit', $product->SKU()) }}">
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
