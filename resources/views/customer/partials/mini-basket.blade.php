<a class="mini-basket top-link top-text"
   rel="nofollow"
   href="{{ route('sales.customer.basket') }}">
    <span class="glyphicon glyphicon-shopping-cart"></span>
    @if (isset($basket))
        <span id="mini-basket-count">
                    {{ $basket->basketItems->count() }}
                </span>
    @endif
</a>
