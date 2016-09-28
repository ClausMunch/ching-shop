@extends('layouts.master')

@section('html-head')
    <link href="{{ elixir('css/staff.css') }}"
          rel="stylesheet"
          property="stylesheet"
          type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('meta-robots')
    noindex,nofollow
@endsection

@section('body-class', 'dashboard')

@section('top')
@include('staff.navbar')
@endsection

@section('body')

    @include('flash::message')

    @yield('pre-content')

    <div class="panel panel-default panel-dashboard">

        <div class="panel-heading">
            <h1 class="panel-title">
                @yield('header')
            </h1>
            @yield('heading')
        </div>

        <div class="panel-body">
            @yield('content')
        </div>

        <div class="panel-footer">
            @yield('footer')
        </div>

    </div>

    @yield('post-content')

@endsection

@push('scripts')
    <script type="application/javascript"
            src="{{ secure_asset(elixir('js/staff.js')) }}"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
            integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
            crossorigin="anonymous"></script>
@endpush
