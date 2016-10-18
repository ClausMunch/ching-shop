@section('page-title')
    {{ $product->name() }} | {{$product->priceF()}} | 3D Pop-Up Greetings Card
@endsection

@section('canonical')
    {{$product->url()}}
@endsection

@section('meta-description')
    {{$product->description()}}. From {{$product->priceF()}} at Ching Shop.
@endsection

@section('html-head')
    @parent
    @include('customer.product.partials.open-graph')
    @include('customer.product.partials.twitter-card')
@endsection

@section('meta-keywords')
    {{$product->name()}},
    3D pop up greetings card,
    @foreach ($product->tags as $tag)
        {{$tag->name}} greetings card
    @endforeach
@endsection
