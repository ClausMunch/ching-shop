@extends('layouts.customer.base')

@section('top')

    <div class="top-band">

        @include('customer.partials.top-band-logo')

        @include('customer.partials.search')

        @include('customer.partials.mini-basket')

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
        <div class="row">
            <div class="col-md-6">
                <a href="https://www.facebook.com/ChingShopCom"
                   title="Like Ching Shop on Facebook" class="icon-link">
                    <span class="icon icon-facebook-with-circle"></span>
                    <span class="sr-only">Ching Shop Facebook</span>
                </a>&nbsp;
                <a href="https://twitter.com/ChingShopCom"
                   title="Follow Ching Shop on Twitter"
                   class="icon-link">
                    <span class="icon icon-twitter-with-circle"></span>
                    <span class="sr-only">Ching Shop Twitter</span>
                </a>
            </div>
            <div class="col-md-6">
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
            </div>
        </div>
    </footer>
@endsection
