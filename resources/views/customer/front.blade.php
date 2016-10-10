@extends('layouts.customer.base')

@section('top')

    <div class="top-band">

        @include('customer.partials.top-band-logo')

        <ul class="top-links top-center">
            <li>
                <a class="top-link top-text" href="/">Home</a>
            </li>
            <li>
                <a class="top-link top-text" href="/cards">Cards</a>
            </li>
            <li>
                <a class="top-link top-text" href="/about">About</a>
            </li>
            <li>
                <a class="top-link top-text" href="/contact">Contact</a>
            </li>
        </ul>

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

    </div>

    <div class="container-fluid">
        <div class="row top-points">
            <div class="col-sm-6 col-md-4 top-points-item">
                <span class="icon icon-truck"></span>&nbsp;
                Free UK Delivery
            </div>
            <div class="col-sm-6 col-md-4 top-points-item">
                <span class="icon icon-esc"></span>&nbsp;
                Easy Returns
            </div>
            <div class="col-sm-6 col-md-4 top-points-item">
                <span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;
                Great Customer Service
            </div>
        </div>
    </div>

@endsection
