@extends('layouts.staff.dashboard')

@section('page-title')
    Tags
@endsection

@section('header')
    Tags
@endsection

@section('content')
    <form method="post"
          id="new-tag-form"
          class="form-inline"
          action="{{ $location->persistActionFor($newTag) }}">
        {{ csrf_field() }}
        {{ method_field($location->persistMethodFor($newTag)) }}
        <div class="form-group {{ $reply->putHasError('name') }}">
            <label for="name">
                New tag:&nbsp;
            </label>
            <input type="text"
                   class="form-control"
                   name="name"
                   id="name"
                   value="{{ $reply->oldInputOr('name', '') }}">
        </div>
        <button type="submit" class="btn btn-success" id="add-new-tag">
            <span class="glyphicon glyphicon-plus"></span>
            Add new tag
        </button>
        @foreach($reply->errorsFor('name') as $error)
            <label class="help-block" for="name">
                {{ $error }}
            </label>
        @endforeach
    </form>
    <hr>
    @if (count($tags))
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        Tag name
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
            @foreach($tags as $tag)
                <tr>
                    <td>
                        {{ $tag->name }}
                    </td>
                    <td>
                        <small>
                            @foreach ($tag->products as $product)
                                <a href="{{ $location->showHrefFor($product) }}">
                                    {{ $product->sku() }}</a>&nbsp;
                            @endforeach
                        </small>
                    </td>
                    <td>
                        <form method="post"
                              id="delete-image-form"
                              action="{{ $location->deleteActionFor($tag) }}">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit"
                                    class="btn btn-sm btn-danger"
                                    id="delete-tag-{{ $tag->id  }}">
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
