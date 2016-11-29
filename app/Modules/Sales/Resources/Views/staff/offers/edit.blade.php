@extends('layouts.staff.dashboard')

@section('page-title')
    Edit `{{$offer->name}}` offer
@endsection

@section('header')
    <small>Edit</small> `{{$offer->name}}`
    <small>offer</small>
@endsection

@section('content')

    @include('sales::staff.offers.form', ['offer' => $offer])

@endsection
