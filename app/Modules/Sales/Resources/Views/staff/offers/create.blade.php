@extends('layouts.staff.dashboard')

@section('page-title')
    New offer
@endsection

@section('header')
    Create a new offer
@endsection

@section('content')

    @include('sales::staff.offers.form', ['offer' => $offer])

@endsection
