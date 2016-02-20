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
            <img src="{{ $image->url() }}"
                 alt="{{ $image->alt_text }}"
                 class="img-responsive img-rounded staff-product-image" />
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
