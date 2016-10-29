@extends('customer.front')

@section('page-title')
    {{$category->name}}
@endsection

@section('body')
    @include('customer.category.breadcrumb', ['leaf' => $category->parent])

    <h1>
        {{$category->name }}
    </h1>

    <hr>

    @unless($category->isLeaf())
        <h2>Sub-categories</h2>
        <p>
            @foreach($category->getDescendants() as $child)
                <a href="{{$child->url()}}">
                    {{$child->name}}
                </a>&nbsp;
            @endforeach
        </p>
        <hr>
    @endunless

    @foreach ($tree as $node)
        @foreach($node->products as $product)
            @include('customer.product.section', ['product' => $product])
        @endforeach
    @endforeach

@endsection
