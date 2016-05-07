@extends('customer.front')

@section('page-title')
    Cards
@endsection

@section('body')

    <h1 class="product-title">Cards</h1>

    @foreach ($products as $product)
        @include('customer.product.section', ['product' => $product])
    @endforeach

@endsection
