@extends('layouts.staff.dashboard')

@section('page-title')
    Print address
@endsection

@section('header')
    Print address
@endsection

@section('content')
    <form method="post" action="{{route('print-generic-address')}}">
        {{csrf_field()}}
        <div class="form-group">
            <label for="address">
                <span class="sr-only">Address</span>
            </label>
            <textarea name="address" id="address"
                      rows="6" cols="42"
                      minlength="18"
                      required></textarea>
        </div>
        <button class="btn btn-success" type="submit">
            <span class="glyphicon glyphicon-print"></span>&nbsp;
            Print
        </button>
    </form>
@endsection
