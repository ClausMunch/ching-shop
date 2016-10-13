<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ef4560" />
    <meta name="google-site-verification"
          content="QW-UE9NaiIzGxJTlPs8jjb6VegQjV1KJGuNxgUvkU1U" />
    <meta http-equiv="Content-Security-Policy"
          content="default-src 'self' https://static.ching-shop.com;
           script-src 'self' https://static.ching-shop.com https://code.jquery.com/ https://*.google-analytics.com;
           img-src 'self' https://static.ching-shop.com https://*.google-analytics.com;
           child-src 'self';
           object-src 'self';
           form-action 'self' {{ config('payment.paypal.base-url') }};
           style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
           font-src 'self' https://fonts.gstatic.com data:;">
    <meta name="robots" content="@yield('meta-robots', 'index,follow')">
    <title>@yield('page-title') | Ching Shop</title>
    @yield('html-head')
</head>
<body class="@yield('body-class')">

    @yield('top')

    <div class="container">
        <div class="page">
            @yield('body')
        </div>
    </div>

    @yield('footer')

<link href='https://fonts.googleapis.com/css?family=Lily+Script+One|Roboto+Slab|Roboto+Condensed:700'
      property='stylesheet'
      rel='stylesheet'
      type='text/css'>
<link rel="icon"
      type="image/png"
      property="icon"
      href="/img/favicon.png" />

@push('scripts')
@if (\App::environment('production'))
<script async defer src="{{ elixir('js/ga.js') }}"></script>
@endif
@endpush

@stack('scripts')

</body>
</html>
