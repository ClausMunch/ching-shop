@extends('layouts.staff.dashboard')

@section('page-title')
    New product
@endsection

@section('header')
    Create a new product
@endsection

@section('content')

    @include('catalogue::staff.products.form', ['product' => $product])

@endsection
