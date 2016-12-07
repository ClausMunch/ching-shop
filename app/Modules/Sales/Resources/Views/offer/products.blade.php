@extends('customer.front')

@section('page-title')
    Special Offers
@endsection

@section('body')
    <h1>
        Products on Special Offer
    </h1>

    <hr>

    {{$products->links()}}

    @foreach($products as $product)
        @include('customer.product.section', ['product' => $product])
    @endforeach

    {{$products->links()}}

@endsection
