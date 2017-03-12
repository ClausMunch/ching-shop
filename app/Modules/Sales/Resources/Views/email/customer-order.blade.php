<img src="{{secure_asset('img/logo-text.png')}}" alt="ChingShop.com"
     style="max-width: 100%;"/>

<hr>

<p>Dear {{$order->address->name}},</p>

<p>Thank you for your ChingShop.com order #{{$order->publicId()}}.</p>

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
    Thank you for shopping at ChingShop.com.
</p>
