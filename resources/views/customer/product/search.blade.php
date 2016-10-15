@extends('customer.front')

@section('page-title')
&ldquo;{{$query}}&rdquo; product search
@endsection

@section('meta-robots')
    noindex,nofollow
@endsection

@section('body')
    <h1>
        &ldquo;{{$query}}&rdquo;<small>search results</small>
    </h1>

    <hr>

    {{ $products->links() }}

    @forelse($products as $product)
        @include('customer.product.section', ['product' => $product])
    @empty
        <p>Sorry, no products were found for &ldquo;{{$query}}&rdquo;.</p>
    @endforelse

    {{ $products->links() }}

@endsection
