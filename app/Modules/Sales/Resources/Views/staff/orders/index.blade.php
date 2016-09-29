@extends('layouts.staff.dashboard')

@section('page-title')
    Orders
@endsection

@section('header')
    All orders
@endsection

@section('content')

<div class="table-responsive">
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Items
                </th>
                <th>
                    Address
                </th>
                <th>
                    Last updated
                </th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders->all() as $order)
            <tr>
                <td>
                    <a href="{{ route('orders.show', $order) }}">
                        {{ $order->publicId() }}
                    </a>
                </td>
                <td>
                    <ul>
                        @if ($order->orderItems)
                        @foreach ($order->orderItems as $item)
                        <li>
                        {{ $item->basketItem->productOption->product->name }}
                        (<strong>{{ $item->basketItem->productOption->label }}</strong>)
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </td>
                <td>
                    @if ($order->address)
                        {{ $order->address->name }}<br>
                        {{ $order->address->line_one }}<br>
                        @if($order->address->line_two)
                            {{ $order->address->line_two }}<br>
                        @endif
                        {{ $order->address->city }}<br>
                        {{ $order->address->post_code }}<br>
                        {{ $order->address->country_code }}
                    @endif
                </td>
                <td>
                    {{ $order->updated_at }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection
