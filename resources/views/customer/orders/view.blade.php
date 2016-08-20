@extends('customer.front')

@section('page-title')
    Order #{{ $order->publicId() }}
@endsection

@section('meta-robots')
    noindex,nofollow
@endsection

@section('body')

    @include('flash::message')

    <h1>Order #<span>{{ $order->publicId() }}</span></h1>

@endsection
