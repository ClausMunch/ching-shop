@extends('layouts.staff.dashboard')

@section('page-title')
    Edit {{ $product->SKU() }}
@endsection

@section('header')
    Edit product {{ $product->SKU() }}
@endsection

@section('content')

    @include('staff.products.form', ['product' => $product])

@endsection
