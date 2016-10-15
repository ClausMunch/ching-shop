@extends('customer.front')

@section('page-title')
    Order #{{ $order->publicId() }}
@endsection

@section('meta-robots')
    noindex,nofollow
@endsection

@section('body')

    <div class="progress">
        <div class="progress-bar"
             role="progressbar"
             aria-valuenow="100"
             aria-valuemin="0"
             aria-valuemax="100"
             style="width: 100%;">
            <span class="sr-only">100% Complete</span>
        </div>
    </div>

    @include('flash::message')

    <h1>Order #<span id="order-id">{{ $order->publicId() }}</span></h1>

    <p>
        Please check that everything looks right below, and retain this page for
        your records.
    </p>

    <div class="row">
        @if ($order->orderItems)
            <div class="col-md-6">
                <h2>What you're getting</h2>
                <ul>
                    @foreach ($order->orderItems as $item)
                        <li>
                            {{ $item->basketItem->productOption->product->name }}
                            (<strong>
                                {{ $item->basketItem->productOption->label }}
                            </strong>)
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($order->address)
            <div class="col-md-6">
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
            </div>
        @endif
    </div>

@endsection
