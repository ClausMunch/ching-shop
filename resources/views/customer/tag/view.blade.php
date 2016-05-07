@extends('customer.front')

@section('page-title')
    {{ $tag->name }}
@endsection

@section('body')
    <h1><small>Tag:</small> {{ $tag->name  }}</h1>

    @foreach($tag->products as $product)
        @include('customer.product.section', ['product' => $product])
    @endforeach

@endsection
