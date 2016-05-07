@extends('layouts.master')

@section('html-head')
    <link href="{{ elixir('css/customer.css') }}"
          rel="stylesheet"
          property="stylesheet"
          type="text/css">
@endsection

@section('body-class', 'body-customer')

@push('scripts')
    <script async defer src="{{ elixir('js/customer.js') }}"></script>
@endpush
