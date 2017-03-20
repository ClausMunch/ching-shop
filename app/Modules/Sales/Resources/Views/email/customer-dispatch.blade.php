<a href="https://www.ching-shop.com/" title="Ching-Shop.com">
    <img src="{{secure_asset('img/logo-text.png')}}" alt="Ching-Shop.com"
         style="width: 256px; max-width: 100%; height: auto;"/>
</a>

<hr>

<p>Dear {{$order->address->name}},</p>

<p>
    Just to let you know that your Ching-Shop.com order
    <a href="{{route('sales.customer.order.view', [$order->publicId()])}}"
       title="View your order">
        #{{$order->publicId()}}
    </a> has now been dispatched.
</p>

<h2>What you're getting</h2>

<ul>
    @foreach ($order->orderItems as $item)
        <li>
            {{$item->basketItem->productOption->product->name}}
            (<strong>
                {{$item->basketItem->productOption->label}}
            </strong>):
            {{$item->linePrice()}}
        </li>
    @endforeach
    @foreach ($order->orderOffers as $offer)
        <li>
            {{$offer->offer_name}}: {{$offer->linePrice()}}
        </li>
    @endforeach
</ul>

<p>Total: {{$order->totalPrice()->formatted()}}</p>

<h2>Where we're sending it</h2>

<address>
    <strong>{{ $order->address->name }}</strong><br>
    {{ $order->address->line_one }}<br>
    @if($order->address->line_two)
        {{ $order->address->line_two }}<br>
    @endif
    {{ $order->address->city }}<br>
    {{ $order->address->post_code }}<br>
    {{ $order->address->country_code }}<br>
</address>

<p>
    You can review your order here:<br>
    <a href="{{route('sales.customer.order.view', [$order->publicId()])}}">
        {{route('sales.customer.order.view', [$order->publicId()])}}
    </a>
</p>

<p>
    Thank you for shopping at Ching-Shop.com.
</p>
