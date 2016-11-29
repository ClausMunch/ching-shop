@extends('layouts.staff.dashboard')

@section('page-title')
    Offers
@endsection

@section('header')
    All offers
@endsection

@section('content')

    <a class="btn btn-success" href="{{route('offers.create')}}">
        <span class="glyphicon glyphicon-plus-sign"></span>
        Create new offer
    </a>

    <hr>

    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>Preview</th>
                <th>Amount</th>
                <th>Effect</th>
                <th>Min. Quantity</th>
                <th>Edit</th>
                <th>Products</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offers->all() as $offer)
                <tr>
                    <td class="text-center">{!! $offer->name->render() !!}</td>
                    <td>{{$offer->amount()}}</td>
                    <td>{{$offer->effect}}</td>
                    <td>{{$offer->quantity}}</td>
                    <td>
                        <a class="btn btn-default"
                           href="{{route('offers.edit', [$offer->id])}}">
                            <span class="glyphicon glyphicon-pencil"></span>
                            &nbsp;
                            Edit
                            <span class="sr-only">{{$offer->name}}</span>
                        </a>
                    </td>
                    <td>
                        <form method="post"
                              action="{{route('offers.put-products', [$offer->id])}}">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <label for="offer-{{$offer->id}}-product-ids">
                                <span class="sr-only">Select products</span>
                            </label>
                            <select class="form-control multiselect"
                                    name="product-ids[]"
                                    id="offer-{{$offer->id}}-product-ids"
                                    multiple="multiple">
                                @foreach($products as $product)
                                    <option value="{{$product->id}}"
                                            id="offer-product-{{$product->id}}"
                                            @if($offer->products->contains($product->id))
                                            selected
                                            @endif
                                    >
                                        {{$product->sku}}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="btn btn-success">
                                <span class="glyphicon glyphicon-check"></span>
                                <span class="sr-only">
                                    Save {{$offer->name}} products
                                </span>
                            </button>
                        </form>
                    </td>
                    <td>
                        <form class="form-confirm"
                              method="post"
                              action="{{route('offers.destroy', [$offer->id])}}">
                            {{csrf_field()}}
                            {{method_field('DELETE')}}
                            <button class="btn btn-sm btn-danger" type="submit">
                                Delete
                                <span class="sr-only">{{$offer->name}}</span>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
