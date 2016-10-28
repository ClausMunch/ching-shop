@extends('customer.front')

@section('page-title')
    {{$category->name}}
@endsection

@section('body')
    @include('customer.category.breadcrumb', ['leaf' => $category])

    <h1>
        <small>Category:</small> {{$category->name }}
    </h1>

    <hr>

    @foreach($category->products as $product)
        @include('customer.product.section', ['product' => $product])
    @endforeach

@endsection
