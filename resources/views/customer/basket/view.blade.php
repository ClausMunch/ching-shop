@extends('customer.front')

@section('page-title')
    Basket ({{ $basket->basketItems->count() }}
    {{ str_plural('item', $basket->basketItems->count()) }},
    {{$basket->totalPrice()}})
@endsection

@section('meta-robots')
    noindex,nofollow
@endsection

@section('body')

    @include('flash::message')

    <h1 class="basket-title">
        <span class="glyphicon glyphicon-shopping-cart"></span>
        Your shopping basket
        ({{ $basket->basketItems->count() }}
        {{ str_plural('item', $basket->basketItems->count()) }})
    </h1>

    <table class="table table-striped basket-items">
        @foreach ($basket->basketItems as $basketItem)
            <tr>
                <td>
                    <a href="{{ route('product::view', [
                            $basketItem->productOption->product->id,
                            $basketItem->productOption->product->slug,
                        ]) }}">
                        {{ $basketItem->productOption->product->name }}
                        <strong>
                            ({{ $basketItem->productOption->label }})
                        </strong>
                    </a>
                </td>
                <td>
                    @if ($basketItem->productOption->images->first())
                        <a href="{{ route('product::view', [
                            $basketItem->productOption->product->id,
                            $basketItem->productOption->product->slug,
                        ]) }}">
                            <img src="{{ $basketItem->productOption->images->first()->sizeUrl('small') }}"
                                 class="basket-product-image"/>
                        </a>
                    @endif
                </td>
                <td class="basket-item-price price">
                    {{ $basketItem->productOption->product->priceF() }}
                </td>
                <td>
                    <form method="post"
                          action="{{ route('sales.customer.remove-from-basket') }}">
                        {{ csrf_field() }}
                        <input name="basket-item-id"
                               value="{{ $basketItem->id }}"
                               type="hidden"/>
                        <button type="submit" class="btn btn-link btn-sm">
                            <span class="glyphicon glyphicon-remove"></span>
                            <span class="sr-only">
                                Remove
                                {{ $basketItem->productOption->product->name }}
                            </span>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        @if ($basket->offers()->collection()->count())
            <tr>
                <th colspan="4"><h4>Discounts</h4></th>
            </tr>
            @foreach($basket->offers()->collection() as $potentialOffer)
                <tr>
                    <td>
                        <strong>{{$potentialOffer->offer()->name}}</strong>
                        on
                        {{$potentialOffer->listComponents()}}, saving you
                        {{$potentialOffer->linePrice()
                            ->negative()->formatted()}}.
                    </td>
                    <td>
                        {!! $potentialOffer->offer()->name->render() !!}
                    </td>
                    <td class="basket-item-price price">
                        {{$potentialOffer->linePrice()->formatted()}}
                    </td>
                    <td></td>
                </tr>
            @endforeach
        @endif
        <tfoot>
        <tr>
            <th colspan="4"><h4>Total</h4></th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="price basket-total">
                @if ($basket->basketItems->count())
                    <span class="basket-total-amount"><!--
                        -->{{$basket->totalPrice()}}
                    </span>
                @endif
            </td>
            <td></td>
        </tr>
        </tfoot>
    </table>

    @if ($basket->basketItems->count())
        <div class="continue">
            <a class="btn btn-lg btn-success continue-button btn-flow"
               href="{{ route('sales.customer.checkout.address') }}"
               rel="nofollow">
                Go to checkout
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
    @endif

@endsection
