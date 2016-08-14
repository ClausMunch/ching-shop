@extends('layouts.customer.base')

@section('meta-robots')
    noindex,nofollow
@endsection

@section('top')

    <div class="top-band">

        @include('customer.partials.top-band-logo')

        <p class="top-center top-text checkout-title">
            Checkout
        </p>

    </div>

@endsection

@section('body')
<div class="progress">
    <div class="progress-bar"
         role="progressbar"
         aria-valuenow="{{ $progress or 0 }}"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width: {{ $progress or 0 }}%;">
        <span class="sr-only">{{ $progress or 0 }}% Complete</span>
    </div>
</div>
<div class="row checkout-section">
    <div class="col-md-8 checkout-section-content">
        @include('flash::message')
        @yield('checkout-section-content')
    </div>
    <div class="col-md-4">
        <div class="checkout-section-summary">
        @if (isset($basket))
            @include('customer.checkout.summary')
        @endif
        </div>
    </div>
</div>
@endsection
