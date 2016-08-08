@extends('layouts.master')

@section('html-head')
    <link href="{{ elixir('css/staff.css') }}"
          rel="stylesheet"
          property="stylesheet"
          type="text/css">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('body-class', 'dashboard')

@section('top')
<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button"
                    class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('staff.dashboard') }}">
                <img alt="Ching Shop"
                     src="/img/logo-plain.svg"
                     class="img-responsive navbar-logo">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown
                    {{ $location->putActive('staff.products') }}">
                    <a href="#" class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        Products <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ $location->putActive(
                                'catalogue.staff.products.index'
                            ) }}">
                            <a href="{{ route('catalogue.staff.products.index') }}">
                                View all
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.products.create'
                            ) }}">
                            <a href="{{ route('catalogue.staff.products.create') }}">
                                Create new
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.products.images.index'
                            ) }}">
                            <a href="{{ route('catalogue.staff.products.images.index') }}">
                                Images
                            </a>
                        </li>
                        <li class="{{ $location->putActive(
                                'catalogue.staff.tags.index'
                            ) }}">
                            <a href="{{ route('catalogue.staff.tags.index') }}">
                                Tags
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>
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
