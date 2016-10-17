@extends('customer.front')

@section('page-title', 'Christmas Cards | 3D Pop-Up Greetings Cards')

@section('body')

    <h1 class="landing-title">
        <small class="landing-title-note">3D Pop-Up</small>
        <br>Christmas Cards
    </h1>

    <hr>

    <p>
        Amaze your friends and family this Christmas with a beautiful 3D pop-up
        Christmas card.
    </p>

    <hr>

    @foreach($products as $product)
        @include('customer.product.section', ['product' => $product])
    @endforeach

@endsection
