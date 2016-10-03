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

@endsection

@push('scripts')
@if (\App::environment('production'))
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-85015770-1', 'auto');
    ga('send', 'pageview');
</script>
@endif
@endpush
