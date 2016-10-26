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
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>
                        Category name
                    </th>
                    <th>
                        Products
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>
                            {{$category->name}}
                        </td>
                        <td>
                            <small>
                                @foreach ($category->products as $product)
                                    <a href="{{route('products.show', [$product->sku])}}">
                                        {{$product->sku}}</a>&nbsp;
                                @endforeach
                            </small>
                        </td>
                        <td>
                            <form method="post"
                                  id="delete-image-form"
                                  action="{{$location->deleteActionFor($category)}}">
                                {{method_field('DELETE')}}
                                {{csrf_field()}}
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        id="delete-tag-{{$category->id}}">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
