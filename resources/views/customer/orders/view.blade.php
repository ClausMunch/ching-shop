@extends('customer.front')

@section('page-title')
    Order #{{ $order->publicId() }}
@endsection

@section('meta-robots')
    noindex,nofollow
@endsection

@section('body')

    @include('flash::message')

    <h1>Order #<span id="order-id">{{ $order->publicId() }}</span></h1>

    @if ($order->address)
        <h2>Where we're sending it</h2>
        <p>
            {{ $order->address->name }}<br>
            {{ $order->address->line_one }}<br>
            @if($order->address->line_two)
                {{ $order->address->line_two }}<br>
            @endif
            {{ $order->address->city }}<br>
            {{ $order->address->post_code }}<br>
            {{ $order->address->country_code }}<br>
        </p>
    @endif

@endsection
