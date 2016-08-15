@extends('customer.checkout.section')

@section('page-title')
    Checkout: Payment Method
@endsection

@section('checkout-section-content')
<a href="{{ route('sales.customer.checkout.address') }}" rel="back">
    &larr; Back to address
</a>
<h2>Payment method</h2>
<p>How would you like to pay for this order?</p>

<form method="post"
      action="{{ route('sales.customer.paypal.start') }}">
    {{ csrf_field() }}
<button type="submit" class="btn btn-success btn-lg payment-choice">
    <span class="icon icon-credit-card inline-icon"></span>
    Pay by credit or debit card
</button>
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
