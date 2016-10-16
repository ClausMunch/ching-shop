@extends('layouts.customer.base')

@section('top')

    <div class="top-band">

        @include('customer.partials.top-band-logo')

        <form class="form top-center" method="get"
              action="{{route('catalogue.search')}}">
            <div class="input-group">
                <input name="query" type="text" class="form-control top-search"
                       value="{{$query or ''}}"
                       placeholder="Search for...">
                <span class="input-group-btn">
                        <button class="btn btn-default"
                                id="search-button"
                                type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                            <span class="sr-only">Search</span>
                        </button>
                    </span>
            </div>
        </form>

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

@section('footer')
    <footer class="footer">
        <ul class="footer-links">
            <li>
                <a class="footer-link" href="/">Home</a>
            </li>
            <li>
                <a class="footer-link" href="/about">About</a>
            </li>
            <li>
                <a class="footer-link" href="/contact">Contact</a>
            </li>
        </ul>
    </footer>
@endsection
