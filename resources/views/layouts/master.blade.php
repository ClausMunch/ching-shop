<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page-title') | Ching Shop</title>
    <link href="{{ elixir('css/ching-shop.css') }}"
          rel="stylesheet"
          property="stylesheet"
          type="text/css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="@yield('body-class')">

    <div class="container">
        @yield('body')
    </div>

<script async src="{{ elixir('js/ching-shop.js') }}"></script>
<link href='https://fonts.googleapis.com/css?family=Lily+Script+One|Roboto+Slab:300|Roboto:300'
      property='stylesheet'
      rel='stylesheet'
      type='text/css'>
</body>
</html>
