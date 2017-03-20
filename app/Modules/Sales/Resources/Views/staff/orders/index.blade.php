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
                    Dispatched?
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders->all() as $order)
                <tr id="order-{{$order->publicId()}}">
                    <td>
                        <a href="{{ route('orders.show', $order) }}">
                            {{ $order->publicId() }}
                        </a>
                    </td>
                    <td class="small">
                        <ul class="list-unstyled">
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
                    <td class="small">{{$order->address}}</td>
                    <td>
                        @if ($order->hasBeenDispatched())
                            <span class="text-success">
                                <span class="icon icon-package"></span>
                                &nbsp;
                                {{$order->dispatchedAt()}}
                                <small>
                                    ({{$order->dispatch()->timeTaken()}})
                                </small>
                            </span>
                        @else
                            <form method="post"
                                  action="{{route('dispatches.store')}}">
                                {{csrf_field()}}
                                <input type="hidden" name="order-id"
                                       value="{{$order->id}}">
                                <button type="submit"
                                        class="btn btn-link"
                                        title="Mark #{{$order->publicId()}} as dispatched">
                                    <span class="icon icon-package"></span>
                                    <span class="sr-only">
                                        Mark #{{$order->publicId()}} as dispatched
                                    </span>
                                </button>
                                <span class="text-danger">
                                    Waiting {{$order->waitingForDispatch()}}&hellip;
                                </span>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
