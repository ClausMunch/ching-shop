@extends('customer.front')

@section('page-title')
    {{$offer->name}} Special Offer Products
@endsection

@section('body')
    <h1>
        {{$offer->name}}
        <small>Special Offer Products</small>
    </h1>

    <hr>

    {{$products->links()}}

    @foreach($products as $product)
        @include('customer.product.section', ['product' => $product])
    @endforeach

    {{$products->links()}}

@endsection
