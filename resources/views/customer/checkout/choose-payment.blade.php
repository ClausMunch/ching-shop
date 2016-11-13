@extends('customer.checkout.section')

@section('page-title')
    Checkout: Payment Method
@endsection

@push('script-src')
'nonce-{{$stripeNonce}}'
@endpush

@section('checkout-section-content')
    <a href="{{ route('sales.customer.checkout.address') }}" rel="back">
        &larr; Back to address
    </a>
    <h2>Payment method</h2>
    <p>How would you like to pay for this order?</p>

    <form action="{{route('sales.customer.stripe.pay')}}"
          method="post" class="stripe-checkout-form">
        {{csrf_field()}}
        <script src="https://checkout.stripe.com/checkout.js"
                nonce="{{$stripeNonce}}"
                class="stripe-button"
                data-key="{{config('services.stripe.key')}}"
                data-amount="{{$basket->subUnitPrice()}}"
                data-name="Ching Shop"
                data-description="Pay by card"
                data-image="https://s3.amazonaws.com/stripe-uploads/acct_17ovOQFnhJNX3n5Bmerchant-icon-1479061561658-logo-brand-colour.svg.png"
                data-locale="auto"
                data-zip-code="true"
                data-currency="gbp">
        </script>
    </form>

    <form method="post"
          action="{{ route('sales.customer.paypal.start') }}">
        {{ csrf_field() }}
        <button type="submit"
                class="btn paypal btn-lg payment-choice"
                id="paypal-button">
            <span class="icon icon-paypal inline-icon"></span>
            Pay with PayPal
        </button>
    </form>

@endsection
