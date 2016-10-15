<h3>What you're getting</h3>
<ul class="summary-list">
    @foreach($basket->basketItems as $basketItem)
        <li>
            {{ $basketItem->productOption->product->name }}
            (<strong>{{ $basketItem->productOption->label }}</strong>)
        </li>
    @endforeach
</ul>

@if($basket->address)
    <h3>Where we're sending it</h3>
    <address>
        <strong>{{ $basket->address->name }}</strong><br>
        {{ $basket->address->line_one }}<br>
        @if($basket->address->line_two)
            {{ $basket->address->line_two }}<br>
        @endif
        {{ $basket->address->city }}<br>
        {{ $basket->address->post_code }}<br>
        {{ $basket->address->country_code }}<br>
    </address>
@endif
