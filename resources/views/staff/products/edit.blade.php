@extends('layouts.staff.dashboard')

@section('page-title')
    Edit {{ $product->sku() }}
@endsection

@section('header')
    Edit product {{ $product->sku() }}
@endsection

@section('content')

    @include('staff.products.form', ['product' => $product])

@endsection
