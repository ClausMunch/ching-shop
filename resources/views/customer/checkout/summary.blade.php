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
<ul class="summary-list">
    <li>{{ $basket->address->name }}</li>
    <li>{{ $basket->address->line_one }}</li>
    @if($basket->address->line_two)
        <li>{{ $basket->address->line_two }}</li>
    @endif
    <li>{{ $basket->address->post_code }}</li>
    <li>{{ $basket->address->country }}</li>
</ul>
@endif
