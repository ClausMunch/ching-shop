@extends('layouts.staff.dashboard')

@section('page-title')
    {{ $product->SKU() }} / {{ $product->name() }}
@endsection

@section('header')
    Product {{ $product->name() }} <small>({{ $product->SKU() }})</small>
@endsection

@section('content')

    @foreach($product->images() as $image)
        <div>
            <img src="{{ $image->url() }}"
                 alt="{{ $image->alt_text }}"
                 class="img-responsive" />
        </div>
    @endforeach

@endsection

@section('footer')
    <a class="btn btn-default"
       href="{{ route('staff.products.edit', $product->SKU()) }}">
        Edit
    </a>
@endsection
