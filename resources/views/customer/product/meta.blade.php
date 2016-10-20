<title>
    {{ $product->name() }}
    | {{$product->priceF()}}
    | 3D Pop-Up Greetings Card
    | Ching Shop
</title>

<link rel="canonical" href="{{$product->url()}}">

<meta name="description" content="
    {{$product->description()}}. From {{$product->priceF()}} at Ching Shop.
">
<meta name="keywords" content="
{{$product->name()}},
    3D pop up greetings card,
    @foreach ($product->tags as $tag)
{{$tag->name}} greetings card
    @endforeach
        ">

@include('customer.product.partials.open-graph')
@include('customer.product.partials.twitter-card')
