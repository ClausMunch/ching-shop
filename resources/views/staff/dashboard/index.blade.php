@extends('layouts.staff.dashboard')

@section('page-title')
    'Staff Dashboard'
@endsection

@section('body')

    <h2>Go-to product bookmark-let</h2>

    <p>Drag this to your bookmarks to get a go-to-product button:</p>

    <a href="javascript:window.location.href = '/catalogue/staff/products/'
+document.getElementById('sku').innerText"
       class="btn btn-default bookmark-let">
        Edit product
    </a>

@endsection
