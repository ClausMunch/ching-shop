<meta property="og:title" content="@yield('page-title')">
<meta property="og:type" content="product">
<meta property="og:image" content="{{$product->mainImage()->sizeUrl('large')}}">
<meta property="og:url" content="{{$product->url()}}">
<meta property="og:description" content="@yield('meta-description')">

<meta property="product:original_price:amount"
      content="{{$product->price()->asFloat()}}">
<meta property="product:original_price:currency"
      content="{{$product->price()->currency}}">
<meta property="product:pretax_price:amount"
      content="{{$product->price()->asFloat()}}">
<meta property="product:pretax_price:currency"
      content="{{$product->price()->currency}}">
<meta property="product:price:amount"
      content="{{$product->price()->asFloat()}}">
<meta property="product:price:currency"
      content="{{$product->price()->currency}}">
<meta property="product:shipping_cost:amount" content="0.0">
<meta property="product:shipping_cost:currency"
      content="{{$product->price()->currency}}">

{{--<meta property="product:weight:value"            content="Sample Weight: Value" >--}}
{{--<meta property="product:weight:units"            content="Sample Weight: Units" >--}}
{{--<meta property="product:shipping_weight:value"   content="Sample Shipping Weight: Value" >--}}
{{--<meta property="product:shipping_weight:units"   content="Sample Shipping Weight: Units" >--}}
{{--<meta property="product:sale_price:amount"       content="Sample Sale Price: " >--}}
{{--<meta property="product:sale_price:currency"     content="Sample Sale Price: " >--}}
{{--<meta property="product:sale_price_dates:start"  content="Sample Sale Price Dates: Start" >--}}
{{--<meta property="product:sale_price_dates:end"    content="Sample Sale Price Dates: End" >--}}
