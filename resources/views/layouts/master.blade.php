<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="QW-UE9NaiIzGxJTlPs8jjb6VegQjV1KJGuNxgUvkU1U" />
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

@yield('scripts')
<link href='https://fonts.googleapis.com/css?family=Lily+Script+One|Roboto+Slab|Roboto+Condensed:700'
      property='stylesheet'
      rel='stylesheet'
      type='text/css'>
<link rel="icon"
      type="image/png"
      property="icon"
      href="/img/favicon.png" />
</body>
</html>
