@extends('layouts.staff.dashboard')

@section('page-title')
    Products
@endsection

@section('header')
    All products
@endsection

@section('content')

    <form method="post"
          action="{{route('catalogue.staff.products.clear-cache')}}">
        {{csrf_field()}}
        {{method_field('DELETE')}}
        <button type="submit" class="btn btn-warning btn-sm">
            <span class="glyphicon glyphicon-floppy-remove"></span>
            Clear product cache
        </button>
    </form>

    <hr>

    {{$products->links()}}

    @if (count($products))
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>
                        SKU
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Image
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <a href="{{ $location->showHrefFor($product) }}">
                                {{ $product->sku() }}
                            </a>
                        </td>
                        <td>
                            {{ $product->name() }}
                        </td>
                        <td class="product-index-images">
                            <img src="{{ $product->mainImage()->sizeUrl('thumbnail') }}"
                                 @if ($product->mainImage()->isSelfHosted())
                                 srcset="{{ $product->mainImage()->srcSet() }}"
                                 @endif
                                 alt="{{ $product->mainImage()->alt_text }}"
                                 class="img-responsive
                                    img-rounded
                                    staff-product-image
                                 "/>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>
            There are no products in the system. Would you like to
            <a href="{{ route('products.create') }}">create one</a>?
        </p>
    @endif

    {{$products->links()}}

@endsection
