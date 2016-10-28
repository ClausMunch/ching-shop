@extends('layouts.staff.dashboard')

@section('page-title')
    Categories
@endsection

@section('header')
    Categories
@endsection

@section('content')
    <form method="post"
          id="new-tag-form"
          class="form-inline"
          action="{{route('categories.store')}}">
        {{csrf_field()}}
        <div class="form-group {{$reply->putHasError('name')}}">
            <label for="name">
                New category:&nbsp;
            </label>
            <input type="text"
                   class="form-control"
                   name="name"
                   minlength="2"
                   maxlength="63"
                   id="name"
                   value="{{$reply->oldInputOr('name', '')}}">
        </div>
        <button type="submit" class="btn btn-success" id="add-new-tag">
            <span class="glyphicon glyphicon-plus"></span>
            Add new category
        </button>
        @foreach($reply->errorsFor('name') as $error)
            <label class="help-block" for="name">
                {{$error}}
            </label>
        @endforeach
    </form>
    <hr>
    {{$categories->links()}}
    <hr>
    @if (count($categories))
        @include(
            'catalogue::staff.categories.display',
            ['allCategories' => $categories]
        )
    @endif
@endsection
