<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page-title') | Ching Shop</title>
    @yield('html-head')
</head>
<body class="@yield('body-class')">

    @yield('top')

    <div class="container">
        @yield('body')
    </div>

<script async defer src="{{ elixir('js/main.js') }}"></script>
@yield('scripts')
<link href='https://fonts.googleapis.com/css?family=Lily+Script+One|Roboto+Slab|Roboto+Condensed:700'
      property='stylesheet'
      rel='stylesheet'
      type='text/css'>
</body>
</html>
