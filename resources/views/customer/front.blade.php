@extends('layouts.customer.base')

@section('top')

    <div class="top-band">

        <a class="top-logo-link" href="/" title="Ching Shop Home">
            <img src="/img/logo-brand-colour.svg"
                 class="top-logo"
                 alt="Ching Shop">
            Ching Shop
        </a>

        <ul class="top-links">
            <li>
                <a class="top-link" href="/">Home</a>
            </li>
            <li>
                <a class="top-link" href="/cards">Cards</a>
            </li>
            <li>
                <a class="top-link" href="/about">About</a>
            </li>
            <li>
                <a class="top-link" href="/contact">Contact</a>
            </li>
        </ul>

        <a class="mini-basket top-link"
           href="{{ route('sales.customer.basket') }}">
            <span class="glyphicon glyphicon-shopping-cart"></span>
            {{ $basket->basketItems->count()  }}
        </a>

    </div>

@endsection
